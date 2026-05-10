@echo off
setlocal enabledelayedexpansion

:: Configuration
set "PROJECT_ID=still-summit-495602-v8"
set "REGION=us-central1"
set "SERVICE_NAME=photobooth-app"
set "BUCKET_NAME=photobooth-uploads-%PROJECT_ID%"
set "AR_REPO=photobooth-repo"
set "IMAGE_NAME=%REGION%-docker.pkg.dev/%PROJECT_ID%/%AR_REPO%/%SERVICE_NAME%:latest"

echo ======================================================
echo  Deploying Photobooth App to Google Cloud Run
echo  with Persistent Cloud Storage (FUSE)
echo ======================================================
echo.

:: 1. Enable Required APIs
echo -^> Enabling required APIs...
call gcloud services enable run.googleapis.com storage.googleapis.com artifactregistry.googleapis.com cloudbuild.googleapis.com --project=%PROJECT_ID%
if %errorlevel% neq 0 echo Warning: API enable command failed or APIs already enabled.

:: 2. Create the Cloud Storage Bucket
echo.
echo -^> Checking/Creating Cloud Storage Bucket: gs://%BUCKET_NAME%...
call gcloud storage ls "gs://%BUCKET_NAME%" >nul 2>&1
if %errorlevel% neq 0 (
    echo    Bucket does not exist. Creating...
    call gcloud storage buckets create "gs://%BUCKET_NAME%" --location=%REGION% --project=%PROJECT_ID%
    
    echo    Making bucket public...
    call gcloud storage buckets add-iam-policy-binding "gs://%BUCKET_NAME%" --member="allUsers" --role="roles/storage.objectViewer"
) else (
    echo    Bucket already exists!
)

:: 3. Configure Service Account Permissions
echo.
echo -^> Configuring Service Account permissions...
for /f "delims=" %%i in ('gcloud projects describe %PROJECT_ID% --format="value(projectNumber)"') do set PROJECT_NUM=%%i

if "%PROJECT_NUM%"=="" (
    echo Error: Could not retrieve Project Number.
    pause
    exit /b 1
)

set "COMPUTE_SA=%PROJECT_NUM%-compute@developer.gserviceaccount.com"
echo    Granting Storage Object Admin role specifically to bucket for: %COMPUTE_SA%

:: PERBAIKAN: Hanya berikan akses Admin ke bucket spesifik, bukan ke seluruh project
call gcloud storage buckets add-iam-policy-binding "gs://%BUCKET_NAME%" --member="serviceAccount:%COMPUTE_SA%" --role="roles/storage.objectAdmin" >nul 2>&1

:: 4. Create Artifact Registry Repository (If not exists)
echo.
echo -^> Checking/Creating Artifact Registry Repository: %AR_REPO%...
call gcloud artifacts repositories describe %AR_REPO% --location=%REGION% --project=%PROJECT_ID% >nul 2>&1
if %errorlevel% neq 0 (
    echo    Repository does not exist. Creating...
    call gcloud artifacts repositories create %AR_REPO% --repository-format=docker --location=%REGION% --description="Docker repository for Photobooth App" --project=%PROJECT_ID%
) else (
    echo    Repository already exists!
)

:: 5. Build and Push Docker Image
echo.
echo -^> Building and pushing Docker image (this might take a few minutes)...
:: PERBAIKAN: Menambahkan titik (.) di akhir agar build merujuk ke direktori saat ini
call gcloud builds submit --tag %IMAGE_NAME% --project %PROJECT_ID% .

:: 6. Deploy to Cloud Run
echo.
echo -^> Deploying to Cloud Run with Gen2 and FUSE Mount...
call gcloud run deploy %SERVICE_NAME% ^
  --image=%IMAGE_NAME% ^
  --region=%REGION% ^
  --project=%PROJECT_ID% ^
  --execution-environment=gen2 ^
  --allow-unauthenticated ^
  --max-instances=1 ^
  --memory=512Mi ^
  --cpu=1 ^
  --add-volume=name=uploads-volume,type=cloud-storage,bucket=%BUCKET_NAME% ^
  --add-volume-mount=volume=uploads-volume,mount-path=/var/www/html/public/uploads

echo.
echo ======================================================
echo  DEPLOYMENT COMPLETE!
echo  Your application is now using Google Cloud Storage as
echo  a persistent local folder.
echo  Uploads will no longer be lost when the server restarts.
echo ======================================================
pause
# photobooth-app/scripts/capture_sony.py

import sys
import os
import subprocess
import logging

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

def capture_photo_with_cli(output_path, filename, sdk_path):
    """
    Menggunakan RemoteCli.exe untuk mengambil foto resolusi tinggi.
    """
    try:
        # --- PERBAIKAN: Gunakan path absolut yang diberikan ---
        cli_path = os.path.join(sdk_path, 'RemoteCli.exe')

        if not os.path.exists(cli_path):
            error_msg = f"Error: RemoteCli.exe tidak ditemukan di {cli_path}"
            logging.error(error_msg)
            print(error_msg, file=sys.stderr)
            return False

        full_output_path = os.path.join(output_path, filename)
        
        command = [
            cli_path,
            "capture",
            f"--output={full_output_path}"
        ]
        
        logging.info(f"Menjalankan perintah: {' '.join(command)}")

        process = subprocess.run(
            command, 
            capture_output=True, 
            text=True, 
            check=True,
            cwd=sdk_path  # Jalankan dari direktori SDK
        )

        logging.info(f"Output CLI: {process.stdout}")
        
        if process.returncode != 0:
            logging.error(f"CLI Error: {process.stderr}")
            print(process.stderr, file=sys.stderr)
            return False

        return os.path.exists(full_output_path)

    except Exception as e:
        error_msg = f"Terjadi error tak terduga: {e}"
        logging.error(error_msg)
        print(error_msg, file=sys.stderr)
        return False

if __name__ == "__main__":
    # Membaca 4 argumen: [nama_skrip, dir_output, nama_file, path_sdk]
    if len(sys.argv) > 3:
        output_dir_arg = sys.argv[1]
        filename_arg = sys.argv[2]
        sdk_path_arg = sys.argv[3] # <-- Argumen baru
        
        if capture_photo_with_cli(output_dir_arg, filename_arg, sdk_path_arg):
            print(f"/uploads/captures/{filename_arg}")
        else:
            sys.exit(1)
    else:
        print("Usage: python capture_sony.py <output_directory> <filename> <sdk_debug_path>", file=sys.stderr)
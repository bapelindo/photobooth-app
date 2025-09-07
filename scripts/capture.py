import subprocess
import sys
import os
from datetime import datetime

def capture_photo(output_dir):
    """
    Captures a photo using gphoto2 and saves it to the specified directory.
    Returns the filename of the captured photo.
    """
    try:
        # Pastikan direktori ada
        os.makedirs(output_dir, exist_ok=True)
        
        # Buat nama file unik berdasarkan timestamp
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        filename = f"photobooth_{timestamp}.png"
        filepath = os.path.join(output_dir, filename)

        # Jalankan gphoto2 untuk mengambil gambar dan mengunduhnya
        command = ["gphoto2", "--capture-image-and-download", f"--filename={filepath}"]
        subprocess.run(command, check=True, capture_output=True, text=True)
        
        return filename
    except subprocess.CalledProcessError as e:
        # Cetak error ke stderr agar bisa ditangkap oleh PHP
        print(f"Error capturing photo: {e.stderr}", file=sys.stderr)
        return None
    except Exception as e:
        print(f"An unexpected error occurred: {e}", file=sys.stderr)
        return None

if __name__ == "__main__":
    # Skrip ini dipanggil dengan argumen direktori output
    if len(sys.argv) > 1:
        output_folder = sys.argv[1]
        captured_file = capture_photo(output_folder)
        if captured_file:
            # Cetak nama file ke stdout agar bisa ditangkap oleh PHP
            print(captured_file)
    else:
        print("Usage: python capture.py <output_directory>", file=sys.stderr)
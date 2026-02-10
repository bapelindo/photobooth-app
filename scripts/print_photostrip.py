import sys
import os
import win32print
import win32ui
from PIL import Image, ImageWin
import io

def print_image_gdi(file_path):
    """
    Menggabungkan gambar menjadi tata letak 4x6 dan mengirimkannya ke printer
    default Windows menggunakan GDI (Graphics Device Interface).

    Method ini lebih kompatibel dengan kebanyakan printer Windows.
    """
    try:
        printer_name = win32print.GetDefaultPrinter()

        # Ukuran kertas dalam inci
        PAPER_WIDTH_IN = 4
        PAPER_HEIGHT_IN = 6
        STRIP_WIDTH_IN = 2
        STRIP_HEIGHT_IN = 6

        with Image.open(file_path) as img:
            # Buat device context untuk printer
            hDC = win32ui.CreateDC()
            hDC.CreatePrinterDC(printer_name)

            # Dapatkan DPI printer
            DPI_X = hDC.GetDeviceCaps(88) # LOGPIXELSX
            DPI_Y = hDC.GetDeviceCaps(90) # LOGPIXELSY

            paper_width_px = int(PAPER_WIDTH_IN * DPI_X)
            paper_height_px = int(PAPER_HEIGHT_IN * DPI_Y)
            strip_width_px = int(STRIP_WIDTH_IN * DPI_X)
            strip_height_px = int(STRIP_HEIGHT_IN * DPI_Y)

            # Resize dan buat photostrip layout
            strip1 = img.resize((strip_width_px, strip_height_px), Image.Resampling.LANCZOS)
            strip2 = strip1.copy()

            final_image = Image.new("RGB", (paper_width_px, paper_height_px), "white")
            final_image.paste(strip1, (0, 0))
            final_image.paste(strip2, (strip_width_px, 0))

            # Gunakan GDI untuk mencetak gambar
            dib = ImageWin.Dib(final_image)

            # Mulai job pencetakan menggunakan device context
            hDC.StartDoc("Photobooth Print")
            hDC.StartPage()

            # Gambar image ke printer dengan ukuran yang sesuai
            dib.draw(hDC.GetHandleOutput(), (0, 0, paper_width_px, paper_height_px))

            hDC.EndPage()
            hDC.EndDoc()

            # Cleanup
            hDC.DeleteDC()

            print(f"Success (GDI): Image '{os.path.basename(file_path)}' sent to printer.")

    except ImportError:
        print("Error: Please install Pillow and pywin32: pip install Pillow pywin32", file=sys.stderr)
    except FileNotFoundError:
        print(f"Error: File not found at {file_path}", file=sys.stderr)
    except Exception as e:
        print(f"An unexpected error occurred: {e}", file=sys.stderr)


def print_image_raw(file_path):
    """
    Menggabungkan gambar menjadi tata letak 4x6 dan mengirimkannya langsung
    ke printer default Windows menggunakan raw BMP data.

    Method ini bisa bekerja untuk beberapa printer yang menerima raw data,
    tapi tidak semua printer mendukung format ini.
    """
    try:
        printer_name = win32print.GetDefaultPrinter()

        # Ukuran kertas dalam inci
        PAPER_WIDTH_IN = 4
        PAPER_HEIGHT_IN = 6
        STRIP_WIDTH_IN = 2
        STRIP_HEIGHT_IN = 6

        with Image.open(file_path) as img:
            hDC = win32ui.CreateDC()
            hDC.CreatePrinterDC(printer_name)
            DPI_X = hDC.GetDeviceCaps(88) # LOGPIXELSX
            DPI_Y = hDC.GetDeviceCaps(90) # LOGPIXELSY

            paper_width_px = int(PAPER_WIDTH_IN * DPI_X)
            paper_height_px = int(PAPER_HEIGHT_IN * DPI_Y)
            strip_width_px = int(STRIP_WIDTH_IN * DPI_X)
            strip_height_px = int(STRIP_HEIGHT_IN * DPI_Y)

            strip1 = img.resize((strip_width_px, strip_height_px), Image.Resampling.LANCZOS)
            strip2 = strip1.copy()

            final_image = Image.new("RGB", (paper_width_px, paper_height_px), "white")
            final_image.paste(strip1, (0, 0))
            final_image.paste(strip2, (strip_width_px, 0))

            # Simpan gambar yang telah diproses ke buffer memori sebagai BMP
            # BMP adalah format sederhana yang lebih mudah diterima oleh printer
            buffered = io.BytesIO()
            final_image.save(buffered, format="BMP")
            bmp_data = buffered.getvalue()

            # Mulai proses pencetakan raw
            hPrinter = win32print.OpenPrinter(printer_name)
            try:
                hJob = win32print.StartDocPrinter(hPrinter, 1, ("Photobooth Print", None, "RAW"))
                try:
                    win32print.StartPagePrinter(hPrinter)
                    win32print.WritePrinter(hPrinter, bmp_data)
                    win32print.EndPagePrinter(hPrinter)
                finally:
                    win32print.EndDocPrinter(hPrinter)
            finally:
                win32print.ClosePrinter(hPrinter)

            print(f"Success (RAW): Image '{os.path.basename(file_path)}' sent to printer.")

    except ImportError:
        print("Error: Please install Pillow and pywin32: pip install Pillow pywin32", file=sys.stderr)
    except FileNotFoundError:
        print(f"Error: File not found at {file_path}", file=sys.stderr)
    except Exception as e:
        print(f"An unexpected error occurred: {e}", file=sys.stderr)


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python print_photostrip.py <path_to_image_file> [method]", file=sys.stderr)
        print("\nMethods:")
        print("  gdi  - Use GDI/Windows Printer Driver (Recommended for most printers)")
        print("  raw  - Use Raw BMP data (For printers that support raw data)")
        print("\nDefault: gdi", file=sys.stderr)
        sys.exit(1)

    image_path_arg = sys.argv[1]
    method = sys.argv[2].lower() if len(sys.argv) > 2 else "gdi"

    if not os.path.exists(image_path_arg):
        print(f"Error: The file path '{image_path_arg}' does not exist.", file=sys.stderr)
        sys.exit(1)

    if method == "raw":
        print_image_raw(image_path_arg)
    elif method == "gdi":
        print_image_gdi(image_path_arg)
    else:
        print(f"Error: Unknown method '{method}'. Use 'gdi' or 'raw'.", file=sys.stderr)
        sys.exit(1)

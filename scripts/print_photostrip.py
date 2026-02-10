import sys
import os
import win32print
import win32ui
from PIL import Image, ImageWin
import io

def print_image(file_path):
    """
    Menggabungkan gambar menjadi tata letak 4x6 dan mengirimkannya ke printer
    default Windows menggunakan GDI (Graphics Device Interface) untuk kompatibilitas
    yang lebih baik dengan printer seperti Epson L3110.
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

            print(f"Success: Image '{os.path.basename(file_path)}' sent to printer.")

    except ImportError:
        print("Error: Please install Pillow and pywin32: pip install Pillow pywin32", file=sys.stderr)
    except FileNotFoundError:
        print(f"Error: File not found at {file_path}", file=sys.stderr)
    except Exception as e:
        print(f"An unexpected error occurred: {e}", file=sys.stderr)


if __name__ == "__main__":
    if len(sys.argv) > 1:
        image_path_arg = sys.argv[1]
        if os.path.exists(image_path_arg):
            print_image(image_path_arg)
        else:
            print(f"Error: The file path '{image_path_arg}' does not exist.", file=sys.stderr)
    else:
        print("Usage: python print_photostrip.py <path_to_image_file>", file=sys.stderr)
# photobooth-app/scripts/liveview_server.py

import asyncio
import websockets
import sys
import logging
import subprocess
import os

# ================================================================================
# KONFIGURASI: Pastikan path ini sudah benar. Gunakan double backslash (\\).
# ================================================================================
SDK_DEBUG_PATH = "C:\\apache\\htdocs\\photobooth-app\\Debug"
# ================================================================================

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

CONNECTED_CLIENTS = set()

async def read_stderr(stream):
    """Membaca dan mencatat output error dari subproses."""
    while True:
        line = await stream.readline()
        if not line:
            break
        logging.error(f"RemoteCli stderr: {line.decode().strip()}")

async def camera_liveview_producer():
    """
    Menjalankan RemoteCli.exe untuk memulai stream live view dan meneruskannya
    ke WebSocket, dengan restart otomatis.
    """
    cli_path = os.path.join(SDK_DEBUG_PATH, 'RemoteCli.exe')
    if not os.path.exists(cli_path):
        logging.error(f"FATAL: RemoteCli.exe tidak ditemukan di '{SDK_DEBUG_PATH}'.")
        return

    command = [cli_path, "liveview", "--format=jpeg"]

    while True:
        logging.info(f"Memulai Live View dengan perintah: {' '.join(command)}")
        process = None
        try:
            process = await asyncio.create_subprocess_exec(
                *command,
                stdout=asyncio.subprocess.PIPE,
                stderr=asyncio.subprocess.PIPE,
                cwd=SDK_DEBUG_PATH
            )

            logging.info(f"Proses Live View CLI dimulai dengan PID: {process.pid}")
            
            # Jalankan task untuk membaca stderr secara terpisah
            stderr_task = asyncio.create_task(read_stderr(process.stderr))

            while True:
                frame_data = await process.stdout.read(8192)
                if not frame_data:
                    break
                
                if CONNECTED_CLIENTS:
                    await asyncio.gather(*[client.send(frame_data) for client in CONNECTED_CLIENTS])

        except asyncio.CancelledError:
            logging.info("Tugas Live View dihentikan.")
            break # Keluar dari loop utama jika dibatalkan
        except Exception as e:
            logging.error(f"Error pada producer: {e}")
        finally:
            if process and process.returncode is None:
                logging.info(f"Menghentikan proses CLI (PID: {process.pid})")
                process.terminate()
                await process.wait()
            
            # Batalkan task stderr jika masih berjalan
            if 'stderr_task' in locals() and not stderr_task.done():
                stderr_task.cancel()

            logging.warning("Stream terputus. Mencoba memulai ulang dalam 5 detik...")
            await asyncio.sleep(5)


async def handler(websocket, path):
    """Menangani koneksi baru dan tugas-tugasnya."""
    logging.info(f"Klien terhubung: {websocket.remote_address}")
    CONNECTED_CLIENTS.add(websocket)
    try:
        await websocket.wait_closed()
    finally:
        logging.info(f"Klien terputus: {websocket.remote_address}")
        CONNECTED_CLIENTS.remove(websocket)

async def main():
    """Fungsi utama untuk menjalankan server."""
    server = await websockets.serve(handler, "0.0.0.0", 8765)
    producer_task = asyncio.create_task(camera_liveview_producer())
    
    try:
        await asyncio.gather(server.wait_closed(), producer_task)
    except asyncio.CancelledError:
        producer_task.cancel()
        await producer_task


if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        logging.info("Server dihentikan.")
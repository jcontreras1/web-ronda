export function imageSize(width, height) {
    const landscape = width > height;
    const scale = Math.min(1, (landscape ? 1280 : 720) / width, (landscape ? 720 : 1280) / height);
    return { width: Math.max(1, Math.round(width * scale)), height: Math.max(1, Math.round(height * scale)) };
}

export async function compressImage(file) {
    if (!['image/jpeg', 'image/png'].includes(file.type)) {
        throw new Error('Seleccioná una foto JPEG o PNG.');
    }
    const url = URL.createObjectURL(file);
    try {
        const image = new Image();
        image.src = url;
        await image.decode();
        const size = imageSize(image.naturalWidth, image.naturalHeight);
        const canvas = document.createElement('canvas');
        canvas.width = size.width;
        canvas.height = size.height;
        const context = canvas.getContext('2d');
        if (!context) throw new Error('El navegador no pudo procesar la foto.');
        context.fillStyle = '#fff';
        context.fillRect(0, 0, size.width, size.height);
        context.drawImage(image, 0, 0, size.width, size.height);
        return canvas.toDataURL('image/jpeg', .6);
    } finally {
        URL.revokeObjectURL(url);
    }
}

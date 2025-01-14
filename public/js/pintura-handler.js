import { openDefaultEditor } from '/pintura/pintura.js';

export function handleImageInput(inputSelector) {
    const fileInput = document.querySelector(inputSelector);

    if (!fileInput) return;

    fileInput.addEventListener('change', () => {
        if (!fileInput.files.length) return;

        const editor = openDefaultEditor({
            imageCropAspectRatio: 1,
            src: fileInput.files[0],
        });

        editor.on('process', (imageState) => {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(imageState.dest);
            fileInput.files = dataTransfer.files;

            editor.destroy();
        });
    });
}

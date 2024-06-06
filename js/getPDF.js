function generatePDF2(filename = 'Компания', format = 'PNG', quality = 0.7) {
    const pdf = new jsPDF('p', 'pt', 'a4');
    const pageHeight = pdf.internal.pageSize.getHeight();
    const pageWidth = pdf.internal.pageSize.getWidth();
    const margin = 10; // Отступ между блоками
    let yPos = margin; // Начальное положение по оси Y

    // Собираем все элементы с классом "employee-item"
    const elements = document.querySelectorAll('.employee-item');

    // Проходимся по каждому элементу и добавляем его содержимое в PDF
    elements.forEach((element, index) => {
        html2canvas(element, { scale: quality }).then(canvas => {
            const imgData = canvas.toDataURL('image/' + format.toLowerCase(), quality);
            const width = element.offsetWidth / 2;
            const height = element.offsetHeight / 2;

            // Проверяем, помещается ли блок на странице
            if (yPos + height + margin > pageHeight) {
                pdf.addPage(); // Добавляем новую страницу
                yPos = margin; // Сбрасываем Y-позицию
            }

            var xPos = (pageWidth - width) / 2; // Выравнивание по центру

            // Добавляем изображение в PDF
            pdf.addImage(imgData, format, xPos, yPos, width, height);
            yPos += height + margin; // Увеличиваем Y-позицию для следующего блока

            // Если это последний элемент, сохраняем PDF
            if (index === elements.length - 1) {
                pdf.save(filename + '.pdf');
            }
        });
    });
}

function generateSolidPDF(filename = 'Компания', format = 'PNG', diagramm_id, quality = 1) {
    const pdf = new jsPDF('p', 'pt', 'a4');
    const pageHeight = pdf.internal.pageSize.getHeight();
    const pageWidth = pdf.internal.pageSize.getWidth();
    

    // Собираем все элементы с классом "employee-item"
    const elements = document.querySelectorAll('#'+diagramm_id);

    // Проходимся по каждому элементу и добавляем его содержимое в PDF
    elements.forEach((element, index) => {
        html2canvas(element, { scale: quality }).then(canvas => {
            const imgData = canvas.toDataURL('image/' + format.toLowerCase(), quality);
            const width = element.offsetWidth / 2;
            const height = element.offsetHeight / 2;

            var xPos = (pageWidth - width) / 2; // Выравнивание по центру

            // Добавляем изображение в PDF
            pdf.addImage(imgData, format, xPos, 10, width, height);

            // Если это последний элемент, сохраняем PDF
            if (index === elements.length - 1) {
                pdf.save(filename + '.pdf');
            }
        });
    });
}




$(document).ready(() => {
    $('.answers').each((index, element) => {
        let rand = Math.floor(Math.random() * 2);
        let rand_answers = $(element).children()[rand];
        $(rand_answers).children('input').prop('checked', true);
    })   
})
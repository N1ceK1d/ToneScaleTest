$(document).ready(() => {
    $('.answers').on('change', (event) => {
        if($(event.target).hasClass('check_input')) {
            $(event.target).parent().parent().parent().removeClass('border-danger');
        }
    })

    $('.end_test_btn').on('click', () => {
        if($('.check_input:checked').length == 105) {
            $('.end_test_btn').prop('type', 'submit');
        } else {
            $('.end_test_btn').prop('type', 'button');
            const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
            const alert = (message, type) => {
                const wrapper = document.createElement('div')
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible position-fixed w-50" role="alert">`,
                    `    <div>${message}</div>`,
                    '    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    notChecked(),
                    '</div>'
                ].join('')
                alertPlaceholder.append(wrapper)
            }
            alert(`Вы не ответили на ${105 - $('.check_input:checked').length} вопросов`, 'danger')
        }
    })
});

function notChecked() {
    let questionNumber = 1;
    if ($('.check_input:checked').length === 0) {
        $('.question').each(function(index, element) {
            const inputs = $(element).find('input');
            const hasUnansweredInput = inputs.filter(':checked').length === 0;
            if (hasUnansweredInput) {
                $(element).addClass('border-danger'); // highlight the question's container
                $(element).get(0).scrollIntoView({behavior: "smooth"}); // scroll to the question
            }
            questionNumber++;
        });
        scrollToFirstUnansweredQuestion();
        return "<hr><p>Вы не ответили на ни один вопрос</p>";
    } else {
        questionNumber = 1; // reset the questionNumber variable
        let notAnsweredQuestionFound = false;
        $('.question').each(function(index, element) {
            const inputs = $(element).find('input');
            const hasUnansweredInput = inputs.filter(':checked').length === 0;
            if (hasUnansweredInput) {
                notAnsweredQuestionFound = true;
                $(element).addClass('border-danger');
                scrollToFirstUnansweredQuestion();
            } else {
                $(element).removeClass('border-danger');
            }
            questionNumber++;
        });
        if (notAnsweredQuestionFound) {
            return `<hr><p>Вы ответили не на все вопросы</p>`;
        } 
    }
}

function scrollToFirstUnansweredQuestion() {
    const questions = $('.question');
    let firstUnansweredQuestion = null;
    questions.each(function() {
        const inputs = $(this).find('input');
        const hasUnansweredInput = inputs.filter(':checked').length === 0;
        if (hasUnansweredInput && !firstUnansweredQuestion) {
            firstUnansweredQuestion = $(this);
        }
    });
    if (firstUnansweredQuestion) {
        firstUnansweredQuestion.get(0).scrollIntoView({behavior: "smooth"});
    }
}
'use strict';

let follow = document.getElementById('next');
let back = document.getElementById('back');

let confirm = document.getElementById('tos-confirm');

follow.addEventListener('click', (e) => {
    e.preventDefault();

    $('#first').fadeOut("fast", function() {
        $('#second').fadeIn("fast");
    });
})

back.addEventListener('click', (e) => {
    e.preventDefault();

    $('#second').fadeOut("fast");
    $('#first').fadeIn("fast");
});

confirm.addEventListener('click', (e) => {
    document.getElementById('tos').checked = true;
});
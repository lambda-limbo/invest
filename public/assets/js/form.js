'use strict';

let follow = document.getElementById('next');
let back = document.getElementById('back');

follow.addEventListener('click', (e) => {
    e.preventDefault();

    let first = document.getElementById('first');
    let second = document.getElementById('second');

    first.style.display = 'none';
    second.style.display = 'block';
})

back.addEventListener('click', (e) => {
    e.preventDefault();
    
    let first = document.getElementById('first');
    let second = document.getElementById('second');

    first.style.display = 'block';
    second.style.display = 'none';
});
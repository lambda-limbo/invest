'use strict';

let follow = document.getElementById('next');

follow.addEventListener('click', (e) => {
    e.preventDefault();

    let first = document.getElementById('first');
    let second = document.getElementById('second');

    first.style.display = 'none';
    second.style.display = 'block';
})
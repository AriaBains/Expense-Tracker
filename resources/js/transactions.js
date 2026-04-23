const searchInput = document.getElementById('searchTransactions').querySelector('.search-input');

searchInput.addEventListener('focusin', () => {
    searchInput.classList.add('active');
})
searchInput.addEventListener('focusout', () => {
    if (searchInput.value === '') searchInput.classList.remove('active');
})
var loggedInMsgModal = document.getElementById('loggedInMsgModal') || null;
if (loggedInMsgModal) {
    loggedInMsgModal.showModal();
}

document.addEventListener('click', e => {
    if (e.target.classList.contains('close-btn')) {
        e.target.closest('.modal').close();
    }
    if (e.target.id === 'infoModalBtn') {
        document.getElementById('infoModal').showModal();
    }
    if (e.target.id === 'incomeModalBtn') {
        document.getElementById('incomeModal').showModal();
    }
    if (e.target.id === 'expenseModalBtn') {
        document.getElementById('expenseModal').showModal();
    }

    // Categories
    if (e.target.id === 'categoriesModalBtn') {
        document.getElementById('categoriesModal').showModal();
    }
    if (e.target.id === 'addCategoryModalBtn') {
        document.getElementById('addCategoryModal').showModal();
        document.getElementById('addCategoryModal').querySelector('input[name=\"name\"]').value = '';
    }
    if (e.target.classList.contains('category-edit-btn')) {
        document.getElementById('editCategoryModal').showModal();
        var categoryName = e.target.closest('.category').querySelector('.category-name').innerText;
        var categoryId = e.target.closest('.category').dataset.categoryId;
        
        var editCategoryForm = document.getElementById('editCategoryForm');
        editCategoryForm.querySelector('.category-id').value = categoryId;
        editCategoryForm.querySelector('.category-name').value = categoryName;
        editCategoryForm.querySelector('.error').innerText = '';
    }

    // Transactions
    if (e.target.classList.contains('editTransactionModalBtn')) {
        const transaction = e.target.closest('.transaction');
        const type = transaction.dataset.transactionType;
        const editTransactionModal = document.getElementById('editTransactionModal');

        editTransactionModal.querySelector('.transaction-id').value = transaction.dataset.transactionId;
        editTransactionModal.querySelector('.transaction-date').value = transaction.dataset.transactionDate;
        editTransactionModal.querySelector('.transaction-name').value = transaction.querySelector('.transaction-name').innerText;
        editTransactionModal.querySelector('.transaction-amount').value = transaction.querySelector('.transaction-amount').innerText.match(/[\d.]+/);

        if (type === 'income') {
            editTransactionModal.querySelector('#incomeType').checked = true;
        }
        else {
            editTransactionModal.querySelector('#expenseType').checked = true;
        }
        
        const invalidInputs = editTransactionModal.querySelectorAll('.invalid-input') || null;
        const inputErrors = editTransactionModal.querySelectorAll('.error') || null;

        if (invalidInputs) {
            invalidInputs.forEach(input => {
                input.classList.remove('invalid-input');
            })
        }

        if (inputErrors) {
            inputErrors.forEach(error => {
                error.innerText = '';
            })
        }
        
        editTransactionModal.showModal();
    }
    if(e.target.classList.contains('deleteTransactionModalBtn')) {
        const deleteTransactionModal = document.getElementById('deleteTransactionModal');

        deleteTransactionModal.querySelector('.transaction-id').value = e.target.closest('.transaction').dataset.transactionId;
        deleteTransactionModal.showModal();
    }

    if (e.target.classList.contains('dropdown-open-btn')) {
        const dropdownsParent = e.target.closest('.dropdown-container');
        const currentlyActiveDropdown = 
        e.target.nextElementSibling.classList.toggle('block!');
    }

    if (e.target.classList.contains('dropdown-btn')) {
        const parent = e.target.closest('.sub-dropdown') || e.target.closest('.group');
        const currentlyActive = parent.querySelector('.dropdown-btn.active');

        const isMultiSelectable = parent.classList.contains('multi-selectable');
        const isDeselectable = parent.classList.contains('deselectable');
        const isActive = e.target.classList.contains('active');

        if (!isMultiSelectable && currentlyActive && currentlyActive !== e.target) {
            currentlyActive.classList.remove('active');
            e.target.classList.add('active');
        }
        else if(isDeselectable && isActive) {
            e.target.classList.remove('active')
        }
        else {
            e.target.classList.add('active');
        }
    }
    
})
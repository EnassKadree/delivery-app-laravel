import axios from 'axios';

const categoriesTable = document.getElementById('categoriesTable');
const addCategoryBtn = document.getElementById('addCategoryBtn');

// Fetch categories
axios.get('/categories').then(response => {
    const categories = response.data.categories;
    categoriesTable.innerHTML = categories.map(category => `
        <tr>
            <td>${category.id}</td>
            <td>${category.name}</td>
            <td><img src="${category.image}" alt="${category.name}" width="50"></td>
            <td>
                <button class="editCategory" data-id="${category.id}">Edit</button>
                <button class="deleteCategory" data-id="${category.id}">Delete</button>
            </td>
        </tr>
    `).join('');
});

// Add event listeners for actions
categoriesTable.addEventListener('click', e => {
    if (e.target.classList.contains('deleteCategory')) {
        const id = e.target.getAttribute('data-id');
        axios.delete(`/categories/${id}`).then(() => {
            location.reload(); // Refresh on delete
        });
    }
});

addCategoryBtn.addEventListener('click', () => {
    // Handle add category functionality (e.g., show a modal or form)
});

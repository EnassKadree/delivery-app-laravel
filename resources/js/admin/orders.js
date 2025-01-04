import axios from 'axios';

const ordersTable = document.getElementById('ordersTable');

// Fetch orders
axios.get('/orders').then(response => {
    const orders = response.data.orders;
    ordersTable.innerHTML = orders.map(order => `
        <tr>
            <td>${order.id}</td>
            <td>${order.customer_name}</td>
            <td>${order.status}</td>
            <td>${order.total}</td>
        </tr>
    `).join('');
});

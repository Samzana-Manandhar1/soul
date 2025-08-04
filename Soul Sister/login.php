<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Product Panel</title>
</head>
<body>
<h1>Admin Product Management</h1>
<div>
    <form id="addProductForm">
        <input name="name" placeholder="Name" required>
        <input name="description" placeholder="Description" required>
        <input name="price" type="number" step="0.01" placeholder="Price" required>
        <input name="image" placeholder="Image URL">
        <input name="stock" type="number" placeholder="Stock" required>
        <button type="submit">Add Product</button>
    </form>
</div>
<div id="products"></div>
<script>
function loadProducts(){
    fetch('admin_products.php')
      .then(res => res.json())
      .then(products => {
        let html = '';
        for(const p of products){
            html += `<div>
                <b>${p.name}</b> $${p.price} (stock: ${p.stock})
                <button onclick="deleteProduct(${p.id})">Delete</button>
            </div>`;
        }
        document.getElementById('products').innerHTML = html;
      });
}
document.getElementById('addProductForm').onsubmit = function(e){
    e.preventDefault();
    let data = {};
    new FormData(this).forEach((v,k)=>data[k]=v);
    fetch('admin_products.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(data)
    }).then(r=>r.json()).then(r=>{ if(r.success) { alert('Added'); loadProducts(); } });
}
function deleteProduct(id){
    fetch('admin_products.php', {
        method:'DELETE',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({id})
    }).then(r=>r.json()).then(r=>{ if(r.success) loadProducts(); });
}
loadProducts();
</script>
</body>
</html>
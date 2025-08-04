function addToCart(productId){
  fetch('cart.php', {
    method:'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({product_id: productId, quantity: 1})
  }).then(r=>r.json()).then(r=>alert('Added to cart!'));
}
function addToFav(productId){
  fetch('favourites.php', {
    method:'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({product_id: productId})
  }).then(r=>r.json()).then(r=>alert('Added to favourites!'));
}
<h2>Add product</h2>

<form action="http://localhost:8080/api-ecom/v1/products/addProduct.php" method="POST">
    <input type="text" name="title" placeholder="Product"> <br>
    <textarea name="description" cols="30" rows="5" placeholder="Description"></textarea><br>
    <label for="price">Price</label><br>
    <input type="number" name="price"><br>
    <label for="quantity">Quantity</label><br>
    <input type="number" name="quantity"><br>
    <input type="submit" value="Add">

</form>
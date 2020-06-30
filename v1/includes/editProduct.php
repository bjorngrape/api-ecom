
<form action="http://localhost:8080/api-ecom/v1/products/editProduct.php?token=<?= $token ?>&action=update&productId=<?= $product['id'] ?>" method="POST">

<input type="text" name="product_title" value="<?= $product['title'] ?>" maxlength="20"><br>
<textarea name="product_description" cols="30" rows="20"><?= $product['description']?></textarea><br>
<input type="number" name="price" value="<?= $product['price']?>"><br>
<input type="number" name="quantity" value="<?= $product['quantity']?>"><br>
<input type="submit" value="Update">

</form>
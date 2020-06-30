<form action="http://localhost:8080/api-ecom/v1/products/viewProducts.php?token=<?= $token ?>&action=search" method="POST">
        <input  type="text" name="searchQ" placeholder="Search">
        <input  type="submit" value="Search">
    </form>

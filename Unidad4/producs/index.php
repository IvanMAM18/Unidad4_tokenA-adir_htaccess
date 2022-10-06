<?php
    include '../app/ProductsController.php';
    include "../app/BrandController.php";

    $producto = new ProductsController();
    $brandController = new BrandController();

    $productos = $producto -> getProducts();
    $brands = $brandController->getBrands();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../layouts/head.php" ?>
</head>
<body>
    <!-- nadvar -->
    <?php include "../layouts/nadvar.php" ?>

    <!-- container -->
    <div class="container-fluid">

        <div class="row">
            <!-- sidebar -->
            <?php include "../layouts/sidebar.php" ?>

            <!-- contentido -->
            <div class="col-lg-10 col-sm-12 bg-white">

                <!-- bread -->
                <div class="border-bottom">
                    <div class="row m-2">
                        <div class="col">
                            <h4>Productos</h4>
                        </div>
                        <div class="col">
                            <button class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#modalAñadirEditar" onclick="addProduct(this)">Añadir producto</button>
                        </div>
                    </div>
                </div>

                <!-- card -->
                <div class="row">
                    <?php if (isset($productos) && count($productos)>0): ?>
                        <?php foreach ($productos as $producto): ?>  
                            <div class="col-sm-6 col-xl-4 col-xxl-3 p-2">
                                <div class="card mb-1 ">
                                <img src="<?= $producto->cover ?>" class="card-img-top img-fluid" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><?php echo $producto->name ?></h5>
                                        <h6 class="card-subtitle text-center"><?= isset($producto->brand->name)?$producto->brand->name:'No Brand' ?></h6>
                                        <p class="card-text" style="text-align: justify;"><?php echo $producto->description ?></p>
                                        <div class="row">
                                            <a data-product='<?= json_encode($producto) ?>' href="#" class="btn btn-warning col-6" data-bs-toggle="modal" data-bs-target="#modalAñadirEditar" onclick="editProduct(this)">Editar</a>
                                            <a href="#" class="btn btn-danger col-6" onclick="remove(<?= $producto->id ?>)">Eliminar</a>
                                            <a href="details.php?slug=<?= $producto->slug ?>" class="btn btn-info col-12">Detalles</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?> 
                    <?php endif ?>
                </div>
                
            </div>
        </div>
    </div>

    <!-- modalAñadirEditar -->
    <div class="modal fade" id="modalAñadirEditar" tabindex="-1" aria-labelledby="modalAñadirEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAñadirEditarLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form enctype="multipart/form-data"  method="post" action="../app/ProductsController.php">
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Name</span>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Product name" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Slug</span>
                      <input id="slug" name="slug" type="text" class="form-control" placeholder="Product slug" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Description</span>
                        <input type="text" id="description" name="description" class="form-control" placeholder="Product description" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Features</span>
                        <input type="text" id="features" name="features" class="form-control" placeholder="Product features" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Brand_id</span>
                        <select class="form-control" id="brand_id" name="brand_id">
                            <?php if (isset($brands) && count($brands)): ?> 
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand->id ?>">
                                    <?= $brand->name ?>
                                </option>
                            <?php endforeach ?>
                            <?php endif ?>
                        </select> 
                    </div>
                    <div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1">@</span>
					  <input name="cover" type="file" class="form-control" placeholder="Product features" aria-label="Username" aria-describedby="basic-addon1">
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                <input type="hidden" id="oculto_input" name="action" value="create">
                <input type="hidden" id="id" name="id" value="create">
                <input type="hidden" value="<?= $_SESSION['global_token'] ?>" name="global_token"> 
            </form>
            </div>
        </div>
    </div>

    <?php include "../layouts/scripts.php" ?>
    
    <script>
        function addProduct(target){
            document.getElementById("oculto_input").value="create";
            document.getElementById("global_token").value="<?= $_SESSION['global_token'] ?>";
        }
        function editProduct(target){
            document.getElementById("oculto_input").value="update";
            let product = JSON.parse(target.getAttribute('data-product'));
            console.log(product.name);
            document.getElementById("name").value=product.name;
            document.getElementById("slug").value=product.slug;
            document.getElementById("description").value=product.description;
            document.getElementById("features").value=product.features;
            document.getElementById("brand_id").value=product.brand_id;
            document.getElementById("id").value=product.id;
            document.getElementById("global_token").value="<?= $_SESSION['global_token'] ?>";

        }
        function remove (id) {
            
            swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                swal("Poof! Your imaginary file has been deleted!", {
                icon: "success",
                });

                var bodyFromData = new FormData();

                bodyFromData.append('id',id);
                bodyFromData.append('action','delete');
                bodyFromData.append('global_token','<?= $_SESSION['global_token'] ?>');

                axios.post('../app/ProductsController.php',bodyFromData)
                .then(function (response){
                    console.log(response);
                })
                .catch(function (error){
                    console.log(error);
                })

                
            } else {
                swal("Your imaginary file is safe!");
            }
            });
        }
    </script>
</body>
</html>
<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/payment-style.css">

<body style="background-color: #000000; display:flex; flex-direction:column; justify-content:center; min-height:100vh;">
    <div class="container-fluid my-auto">
        <div class="col-12">
            <div class="row">
                <div class="col text-center">
                    <img src="../assets/img/icons/Order-Placed-Successfully.png" alt="order-placed-successfully" style="width: 170px;">
                </div>
            </div>
            <div class="row mt-5 mb-1 text-white text-center">
                <div class="col-12">Order Placed Successfully!</div>
            </div>
            <div class="row mb-4 text-white small-text text-center">
                <div class="col-12">Congratulations! Your order has been placed. <br> You can track your order number #123454!</div>
            </div>
            <div class="row mb-2 p-4">
                <a class="small-text text-center" href="tab5-receipt.php?id=<?= $_GET['id']; ?>">
                    <div class="col">
                        <div class="bg-white d-flex align-items-center justify-content-center rounded-pill border border-white text-purple" style="height: 30px;">Track order</div>
                    </div>
                </a>
            </div>
            <div class="row">
                <a class="text-white small-text text-center" id="continue-shopping">
                    <div class="col-12">
                        Continue shopping
                    </div>
                </a>
            </div>
        </div>
    </div>


    <!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script>
    document.getElementById("continue-shopping").addEventListener("click", function() {
        let f_pin = "";
        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = localStorage.getItem("save_f_pin") ? localStorage.getItem("save_f_pin") : localStorage.getItem("f_pin");
        }
        window.location = 'tab1-main.php?f_pin=' + f_pin;
    })
</script>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            box-sizing: border-box;
            }
            .container {
            padding: 1em;
            }
            form {
            margin: 30px auto;
            width: 300px;
            }
            input {
            display: block;
            margin-bottom: 1em;
            background-size: 1.2em;
            background-repeat: no-repeat;
            background-position: 2% 50%;
            transition: all ease-in-out 300ms;
            &:focus {
                outline: 0 !important;
                box-shadow: 0 0 3px 1px red;
            }
            }

            input[name="name"] {
            background-image: url("http://www.marketingextremist.com/wp-content/uploads/2014/05/How-to-Change-a-Username-in-WordPress.jpg");
            }
            input[name="email"] {
                background-image: url("https://conceptdraw.com/a2623c3/p22/preview/640/pict--mail-app-icons-vector-stencils-library");
            }
            input[name="site"] {
                background-image: url("http://blog.harbinger-systems.com/images/Apps-Home-icon.png");
            }
            .btn-submit, input {
            padding: .5em 2em;
            border: 1px solid #777;
            border-radius: .3em;
            font-size: 1.2em;
            &:focus {
                outline: 0 !important;
                box-shadow: 0 0 3px 2px lightblue;
            }
            }
    </style>
</head>
<body>
<div class="container">
  <form action="{{ route('teste.submit') }}" enctype="multipart/form-data" method="POST">
    @csrf
    <input type="file" name="file" />
    <button type="submit" class="btn-submit">Submit</button>
  </form>
</div>

</body>
</html>

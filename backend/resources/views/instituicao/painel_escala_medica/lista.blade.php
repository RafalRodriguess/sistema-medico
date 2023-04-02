    <style>
        .header {
            text-align: center;
            font-size: 300%;
            padding: 4%;
            color: #fff;
        }
        .body {
            color: black;
            text-align: center;
            font-size:210%;
            padding-top: 20px;
            width: 104%;
            margin-top: 6%;
        }
        .imagem{
            background-color: white;
            text-align: center;
            height: 15%;
            width: 135%;
        }
        .label01{

        }
        input{
            position:relative;
            float: right;
        }
        body, row {height:100%;}

    </style>

        <head>
            <title>Escala Médica</title>
        </head>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <div class="row" style="background-color: #00006f">
            <div class="col-md-5" >
                <div class="header">Corpo Clinico de Plantão</div>
            </div>
            <div class="col-md-1">
                <div class="imagem" ><a href="javascript:void(0)"><img style="width: 60%;" src="../material/assets/images/asasaude.png" alt="user-img" class=""> </a></div>
            </div>
            <div class="col-md-4">
                <div class="header" >Corpo Clinico Alcançavel</div>
            </div>
            <div class="col-md-2">
                <div class="header">
                    <form>
                        <input type="button" value="Voltar" style="background-color: blue;font-size: 50%"onClick="history.go(-1)">
                    </form>
                </div>
            </div>
        </div>


        <div class="row">
            @foreach ($escalasMedicas as $escalaMedica )
            <div class="col-md-5" >

                    <div class="body">
                        <label class="label01" for="clinico">{{$escalaMedica->especialidade->descricao}}</label>
                        <span>Dr(a). {{$escalaMedica->nome}} </span>
                    </div>

            </div>
            @endforeach
        </div>

<script>
    setTimeout(function() {
        window.location.reload(1);
    }, 180000);
</script>

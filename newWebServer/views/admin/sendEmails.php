<?php
    $this->title = 'Enviar Emails';
    $this->params['breadcrumbs'][] = $this->title;
?>

<?php if( $updateResult["result"] == "success" ): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button
        <strong>EMAILS ENVIADOS!</strong>
        <br />
    </div>    
<?php endif; ?>    

<?php if( $updateResult["result"] == "error" ): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button
        <strong>ERRO!</strong> Algo aconteceu ao salvar no banco de dados. Entre em contato com o responsável pelo sistema.
    </div>
<?php endif; ?>

<button  id="botaoEnviarEmails" type="button" class="btn btn-primary btn-lg">ENVIAR EMAILS</button>
&nbsp&nbsp&nbsp<h3 style="display:inline;">[ <a href="#"><?php echo count($result) ?></a> ] notícias classificadas e não enviadas.</h3>
<br/>
<br/>


<div class="table-responsive table-striped ">
  <table class="table table-hover">
    <thead>
        <tr>
            <th> institute_name </th>
            <th> broadcaster_name </th>
            <th> news_id </th>
            <th> news_title </th>
            <th> news_content </th>
        </tr>
    </thead>
    <tbody> 
<?php
function debug() {
    $trace = debug_backtrace();
    $rootPath = dirname(dirname(__FILE__));
    $file = str_replace($rootPath, '', $trace[0]['file']);
    $line = $trace[0]['line'];
    $var = $trace[0]['args'][0];
    $lineInfo = sprintf('<div><strong>%s</strong> (line <strong>%s</strong>)</div>', $file, $line);
    $debugInfo = sprintf('<pre>%s</pre>', print_r($var, true));
    print_r($lineInfo.$debugInfo);
}

foreach ($result as $position => $news) {
    # code...
     echo "<tr>";
        echo "<td>" . $news["institute_name"] . "</td>"; 
        echo "<td>" . $news["broadcaster_name"] . "</td>"; 
        echo "<td>" . $news["news_id"] . "</td>"; 
        echo "<td>" . $news["news_title"] . "</td>"; 
        echo "<td>" . $news["news_content"] . "</td>"; 
    echo "</tr>"; 
}
?>
</tbody> 
</table>
</div>

<script>
$("#botaoEnviarEmails").on("click", function(){

        var redirect = '/index.php?r=admin/sendemails';

        // jquery extend function
        $.extend(
        {
            redirectPost: function(location, args)
            {
                var form = '';
                $.each( args, function( key, value ) {
                    //console.log( "chave:" + key );
                    //console.log( "valor:" + value ";
                    value = value.split('"').join("\"");
                    form += '<input type="hidden" name="'+key+'" value=\''+value+'\'>';
                });
                $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
            }
        });

        $.redirectPost( redirect, { 
                                        enviar: "sim"
                                  }
                      );

    });
</script>
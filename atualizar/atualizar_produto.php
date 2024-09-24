<?PHP
require_once("../conexao/banco.php");

$id 	= $_REQUEST['txt_codigo'];
	
$nome 	= $_REQUEST['txt_nome'];
$tipo 	= $_REQUEST['txt_tipo'];
$preco 	= $_REQUEST['txt_preco'];
$descricao 	= $_REQUEST['txt_descricao'];
$validade 	= $_REQUEST['txt_validade'];

$sql = "update tb_produtos set  
					pro_nome = '$nome', 
					pro_descricao = '$descricao',
					pro_tipo = '$tipo',
					pro_preco = '$preco',
					pro_validade = '$validade'
				where 
					pro_codigo = '$id'";
								
mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_produto.php");

?>

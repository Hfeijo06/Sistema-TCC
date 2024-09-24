<?PHP

require_once('../conexao/banco.php');

$nome 	= $_REQUEST['txt_nome'];
$tipo 	= $_REQUEST['txt_tipo'];
$preco 	= $_REQUEST['txt_preco'];
$descricao 	= $_REQUEST['txt_descricao'];
$validade 	= $_REQUEST['txt_validade'];

$sql = "insert into tb_produtos (pro_nome, pro_tipo, pro_preco, pro_descricao, pro_validade) 
								values ('$nome', '$tipo', '$preco', '$descricao', '$validade')";

mysqli_query($con, $sql) or die ("Erro na sql!") ;

header("Location: ../consulta_produto.php");

?>




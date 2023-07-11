<!DOCTYPE html>

<?php include "baglan.php"; 
session_start();
if ($_POST['gönderi']) {
	$_SESSION['filtre']=$_POST['gönderi'];
}
//KATEGORİLER
if ($_POST['url_tara_lis']) {
	$url_böl=explode("#*", $_POST['url_tara_lis']);
	$_SESSION['url_tara_lis']=$_POST['url_tara_lis'];
	$_SESSION['url_tara_sorgu']=" and urun_yer='".$url_böl[0]."'";
	$_SESSION['nereye']="urunler_".$url_böl[1];
	$_SESSION['ana_isim']=$url_böl[1];
	$_SESSION['urun_yer']=$url_böl[0];
}

if ($_POST['url_tara_lis']=="hepsi") {
	$_SESSION['url_tara_lis']="hepsi";
	$_SESSION['url_tara_sorgu']="";
	$_SESSION['nereye']="urunler_".$_SESSION['hedef_pazar'];
}
//FBA
if ($_POST['fba']=="fba") {
	$_SESSION['sorgu_fba']= " and urun_fba=1";
}

if ($_POST['fba']=="fbm") {
	$_SESSION['sorgu_fba']= " and urun_fba is null";
}
if ($_POST['fba']=="hepsi_fba") {
	$_SESSION['sorgu_fba']= "";
}

if ($_POST['gönderi_']) {
	$_SESSION['filtre_']=$_POST['gönderi_'];
}

//Ac
if ($_POST['ac']=="ca") {
	$_SESSION['sorgu_ac']= " and urun_ac=1";
}

if ($_POST['ac']=="ca_yok") {
	$_SESSION['sorgu_ac']= " and urun_ac is null";
}
if ($_POST['ac']=="hepsi_ca") {
	$_SESSION['sorgu_ac']= "";
}

//Cupuon
if ($_POST['cp']=="cp_ekle") {
	$_SESSION['sorgu_kupon']= " and urun_kupon!=''";
}

if ($_POST['cp']=="cp_yok") {
	$_SESSION['sorgu_kupon']= " and urun_kupon=''";
}
if ($_POST['cp']=="hepsi_cp") {
	$_SESSION['sorgu_kupon']= "";
}

//CUpon bitiş

if ($_POST['gönderi_']) {
	$_SESSION['filtre_']=$_POST['gönderi_'];
}



//USA
if (isset($_POST['max_fiyat'])) {
	$_SESSION['max_fiyat']=$_POST['max_fiyat'];
	$_SESSION['mix_fiyat']=$_POST['mix_fiyat'];

	$_SESSION['sorgu1']= " and urun_fiyat>=".$_POST['mix_fiyat']." and urun_fiyat<=".$_POST['max_fiyat'];

}

if ($_SESSION['mix_fiyat']=="" or $_SESSION['max_fiyat']=="") {
	$_SESSION['sorgu1']="";
	$_SESSION['max_fiyat']="";
	$_SESSION['mix_fiyat']="";

	
}


if (isset($_POST['max_puan'])) {
	$_SESSION['max_puan']=$_POST['max_puan'];
	$_SESSION['min_puan']=$_POST['min_puan'];

	$_SESSION['sorgu_puan']= " and urun_puan>=".$_POST['min_puan']." and urun_puan<=".$_POST['max_puan'];

}




if ($_SESSION['min_puan']=="" or $_SESSION['max_puan']=="") {
	$_SESSION['sorgu_puan']="";
	$_SESSION['max_puan']="";
	$_SESSION['min_puan']="";

	
}




if (isset($_POST["secimvar1"])) {
	$_SESSION["secimvar1"]=$_POST["secimvar1"];
	$_SESSION['sorgu1']="";
	$_SESSION['sorgu_fba']="";
	$_SESSION['sorgu_puan']="";
	$_SESSION['max_fiyat']="";
	$_SESSION['mix_fiyat']="";
	$_SESSION['min_puan']="";
	$_SESSION['max_puan']="";
	$_SESSION['min_bsr']="";
	$_SESSION['max_bsr']="";
	

}
if ($_SESSION['filtre_']==" ") {
	unset($_SESSION['filtre_']);
	

}

$nereden="urunler_"."com";


//BSR
if (isset($_POST['max_bsr'])) {
	$_SESSION['max_bsr1']=$_POST['max_bsr'];
	$_SESSION['min_bsr']=$_POST['min_bsr'];
}

if (isset($_SESSION['sorgu_bsr'])) {

	$_SESSION['sorgu_bsr']= "  urun_bil_s1 between ".$_SESSION['min_bsr']." and ".$_SESSION['max_bsr1'].$_SESSION['url_tara_sorgu'].$_SESSION['sorgu1'].$_SESSION['sorgu_puan'].$_SESSION['sorgu_ac'].$_SESSION['sorgu_fba']." and urun_tarandi=1 and kullanici_id=".$_SESSION['kullanici_id']." or urun_bil_s2 between ".$_SESSION['min_bsr']." and ".$_SESSION['max_bsr1'].$_SESSION['url_tara_sorgu'].$_SESSION['sorgu1'].$_SESSION['sorgu_puan'].$_SESSION['sorgu_ac'].$_SESSION['sorgu_fba']." and urun_tarandi=1 and kullanici_id=".$_SESSION['kullanici_id']." or urun_bil_s3 between ".$_SESSION['min_bsr']." and ".$_SESSION['max_bsr1'].$_SESSION['url_tara_sorgu'].$_SESSION['sorgu1'].$_SESSION['sorgu_puan'].$_SESSION['sorgu_ac'].$_SESSION['sorgu_fba']." and urun_tarandi=1 and kullanici_id=".$_SESSION['kullanici_id'];

	$sorfu_data="SELECT * from ".$_SESSION['nereye']." where and durum='onaylandi' ".$_SESSION['sorgu_bsr'];
}
if ($_SESSION['min_bsr']=="" or $_SESSION['max_bsr1']=="") {
	$sorfu_data="SELECT * from ".$_SESSION['nereye']." where urun_tarandi=1 and durum='onaylandi' and kullanici_id=".$_SESSION['kullanici_id'].$_SESSION['sorgu1'].$_SESSION['sorgu_puan'].$_SESSION['sorgu_ac'].$_SESSION['sorgu_fba'].$_SESSION['url_tara_sorgu']." order by urun_asin";
}
//TARAMA

$tara=$db->prepare("SELECT count(*) from ".$_SESSION['nereye']." where urun_tarandi=1 ".$_SESSION['url_tara_sorgu']." and kullanici_id=?");
$tara->execute([$_SESSION['kullanici_id']]);

$tara_ü=$tara->fetchColumn();

//ONAYLANMA

$onay_tara=$db->prepare("SELECT count(*) from ".$_SESSION['nereye']." where urun_tarandi=1 ".$_SESSION['url_tara_sorgu']." and kullanici_id=? and durum='onaylandi'");
$onay_tara->execute([$_SESSION['kullanici_id']]);

$onay_ü=$onay_tara->fetchColumn();
//Fiyat

$onay_tara=$db->prepare("SELECT count(*) from ".$_SESSION['nereye']." where urun_tarandi=1 ".$_SESSION['url_tara_sorgu']." and kullanici_id=? and durum='fiyat'");
$onay_tara->execute([$_SESSION['kullanici_id']]);

$ürün_f=$onay_tara->fetchColumn();


//Puan

$onay_tara=$db->prepare("SELECT count(*) from ".$_SESSION['nereye']." where urun_tarandi=1 ".$_SESSION['url_tara_sorgu']." and kullanici_id=? and durum='puan'");
$onay_tara->execute([$_SESSION['kullanici_id']]);
$ürün_p=$onay_tara->fetchColumn();


//SPONSORLU ÜRÜN

$onay_tara=$db->prepare("SELECT count(*) from ".$_SESSION['nereye']." where urun_tarandi=1 ".$_SESSION['url_tara_sorgu']." and kullanici_id=? and durum='sponsor'");
$onay_tara->execute([$_SESSION['kullanici_id']]);
$ürün_sp=$onay_tara->fetchColumn();



?>
<div align="center">
	<div class="row container text-center">
		<div style="border-radius: 10px;" class="col-md-2 bg-secondary ">
			<br> <i class="fa-brands fa-amazon fa-2xl" style="color: #00060f;"></i><br>
			<?php echo $tara_ü; ?> Ürün tarandi


			<br><br>
		</div>

		<div  style="border-radius: 10px; margin-left: 1%" class="col-md-2 bg-secondary">
			<br> <i class="fa-solid fa-square-check fa-2xl" style="color: #0f9f46;"></i><br>
			<?php echo $onay_ü; ?> Ürün Onaylandi 
			<br><br>

		</div>

		<?php if ($ürün_p!=0) {?>
			<div style="border-radius: 10px; margin-left: 1%" class="col-md-2 bg-secondary">
				<br>
				<i class="fa-solid fa-xmark fa-2xl" style="color: #db0000;"></i><br>
				<?php echo  $ürün_p?> Ürün Puanı Uyuşmadı
				<br><br>

			</div>
		<?php } ?>

		<?php if ($ürün_f!=0) {?>
			<div style="border-radius: 10px; margin-left: 1%" class="col-md-2 bg-secondary">
				<br>
				<i class="fa-solid fa-xmark fa-2xl" style="color: #db0000;"></i><br>
				<?php echo  $ürün_f?> Ürün Fiyatı Uyuşmadı 
				<br><br>

			</div>
		<?php } ?>

		<?php if ($ürün_sp!=0) {?>
			<div style="border-radius: 10px; margin-left: 1%" class="col-md-2 bg-secondary">
				<br>
				<i class="fa-solid fa-xmark fa-2xl" style="color: #db0000;"></i><br>
				<?php echo  $ürün_sp?> Ürün Sponsorlu Değil 
				<br><br>

			</div>
		<?php } ?>
	</div>
</div>
	<br><br>
	<?php  




	if ($_SESSION['filtre']) {





//CANADA
		$urun_ca=$db->prepare($sorfu_data);
		$urun_ca->execute([1,$_SESSION['kullanici_id']]);

		?>
		<html>
		<head>
			<meta charset="utf-8">

			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title></title>


			<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
			<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
			<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">


		</head>
		<body>


			<div>

				<table id="example" class="table table-hover  dt-responsive nowrap" style="width:100%">
					<thead>



						<tr style="font-size: 10px" >
							<th rowspan="1"></th>

							<?php if (strstr($_SESSION['filtre'],"urun_resim")) { ?><th rowspan="1">Görsel</th> <?php } ?>

							<?php if (strstr($_SESSION['filtre'],"urun_ad")) { ?><th rowspan="1">Açıklama</th><?php } ?>

					<?php  //ASİN
					if (strstr($_SESSION['filtre'],"urun_asin")) {?><th rowspan="1">Asin</th><?php } ?>
						<?php if (strstr($_SESSION['filtre'],"urun_puan")) {?>  <th rowspan="1">Puan</th><?php } ?>
					<?php if (strstr($_SESSION['filtre'],"urun_puan")) {?>  <th rowspan="1">Yorum</th> <?php } ?>
					<?php if (strstr($_SESSION['filtre'],"urun_bsr")) {?>  <th rowspan="1">BSR</th><?php } ?>

					<?php if (strstr($_SESSION['filtre'],"urun_fiyat")) {?>  <th rowspan="1">Fiyat</th> <?php } ?>
					<?php if (strstr($_SESSION['filtre'],"urun_fiyat")) {?>  <th rowspan="1">H Fiyat</th> <?php } ?>
					<?php if (strstr($_SESSION['filtre'],"urun_stok")) {?>   <th rowspan="1">Stok</th><?php } ?>
					<?php if (strstr($_SESSION['filtre'],"urun_fba_d")) {?>   <th rowspan="1">FBA/FBM</th><?php } ?>
					<?php if (strstr($_SESSION['filtre'],"satici_sayisi")) {?>  <th rowspan="1">Satıcı sayısı</th><?php } ?>
					<?php if (strstr($_SESSION['filtre'],"marka")) {?>  <th rowspan="1">Marka</th><?php } ?>
					<?php if (strstr($_SESSION['filtre'],"urun_cp")) {?>  <th rowspan="1">Kupon</th><?php } ?>
					
					
				</tr>
			</thead>
			<tbody>
				<?php while ($urun_ca_c=$urun_ca->fetch(PDO::FETCH_ASSOC))

				{
					$urun_usa=$db->prepare("SELECT * from $nereden where urun_tarandi=? and kullanici_id=? and urun_asin=?".$_SESSION['sorgu_kupon']);
					$urun_usa->execute([1,$_SESSION['kullanici_id'],$urun_ca_c["urun_asin"]]);

					while ($urun_usa_c=$urun_usa->fetch(PDO::FETCH_ASSOC)) {


						$böl_ad=explode(" ",$urun_ca_c["urun_ad"]);
						$usa_by=$urun_usa_c["urun_bil_y1"];
						if (strstr($urun_usa_c["urun_bil_y1"],"See")) {
							$böl_bsr_usa=explode("See",$urun_usa_c["urun_bil_y1"]);
							$usa_by=$böl_bsr_usa[0];
						}
						$ca_by=$urun_ca_c["urun_bil_y1"];

						if (strstr($urun_ca_c["urun_bil_y1"],"See")) {
							$böl_bsr_ca=explode("See",$urun_ca_c["urun_bil_y1"]);
							$ca_by=$böl_bsr_ca[0];
						}
						$satici_sayisi=$urun_ca_c["satici_sayisi"];
						if ($urun_ca_c["satici_sayisi"]=="") {
							$satici_sayisi=1;
						}



						


						?>
						<tr style="font-size: 10px">
							<td></td>
							<?php if (strstr($_SESSION['filtre'],"urun_resim")) { ?><td><img src="<?php echo $urun_usa_c["urun_resim"]?>" width="30" height="30"></td> <?php } ?>

							<?php if (strstr($_SESSION['filtre'],"urun_ad")) {?><td>
								<?php echo $böl_ad[8]." ".$böl_ad[9]." ".$böl_ad[10]."<br> ".$böl_ad[11]." ".$böl_ad[12]."<br>".$böl_ad[13]." ".$böl_ad[14]."<br> ".$böl_ad[15]." ".$böl_ad[16]."<br>".$böl_ad[17].$böl_ad[18];
							?></td>
						<?php } ?>


							<?php  //ASİN
							if (strstr($_SESSION['filtre'],"urun_asin")) {?><td>
								<a target="_blank" href="https://www.amazon.<?php echo $_SESSION['ana_isim']."/dp/".$urun_ca_c["urun_asin"]?>"><?php echo $urun_ca_c["urun_asin"];?></a>

								</td><?php } ?>

								<?php if (strstr($_SESSION['filtre'],"urun_puan")) {?><td ><?php  echo substr($urun_ca_c["urun_puan"], 0,3) ;?>⭐️</td><?php } ?>

								<?php if (strstr($_SESSION['filtre'],"urun_puan")) {
									?>

									<td><?php 
									$böl_yo=explode(".",$urun_ca_c["yorum_sayisi"]);
									if (substr($böl_yo[1],2)) {
										echo number_format($urun_ca_c["yorum_sayisi"],3); } else {echo ceil($urun_ca_c["yorum_sayisi"]);}





									?> <br>Yorum</td>


								<?php } ?>
								<?php 

								$böl_yo1=explode(".",$urun_ca_c["urun_bil_s1"]);
								if (substr($böl_yo1[1],2)) {
									$tk1= number_format($urun_ca_c["urun_bil_s1"],3); } else {$tk1=ceil($urun_ca_c["urun_bil_s1"]);}


									$böl_yo2=explode(".",$urun_ca_c["urun_bil_s2"]);
									if (substr($böl_yo2[1],2)) {
										$tk2= number_format($urun_ca_c["urun_bil_s2"],3); } else {$tk2=ceil($urun_ca_c["urun_bil_s2"]);}


										$böl_yo3=explode(".",$urun_ca_c["urun_bil_s3"]);
										if (substr($böl_yo3[1],2)) {
											$tk3= number_format($urun_ca_c["urun_bil_s1"],3); } else {$tk3=ceil($urun_ca_c["urun_bil_s3"]);}


											$böl_yo4=explode(".",$urun_usa_c["urun_bil_s1"]);
											if (substr($böl_yo4[1],2)) {

												$tk4= number_format($urun_usa_c["urun_bil_s1"],3); } else {$tk4=ceil($urun_usa_c["urun_bil_s1"]);}



												$böl_yo5=explode(".",$urun_usa_c["urun_bil_s2"]);
												if (substr($böl_yo5[1],2)) {
													$tk5= number_format($urun_usa_c["urun_bil_s2"],3); } else {$tk5= ceil($urun_usa_c["urun_bil_s2"]);}



													$böl_yo6=explode(".",$urun_usa_c["urun_bil_s3"]);
													if (substr($böl_yo6[1],2)) {
														$tk6= number_format($urun_usa_c["urun_bil_s3"],3); } else {$tk6= ceil($urun_usa_c["urun_bil_s3"]);}

														?>


														<?php if (strstr($_SESSION['filtre'],"urun_bsr")) {?>  <td >USA : <br><?php echo $tk4.$usa_by."<br>".$tk5.$urun_usa_c["urun_bil_y2"]."<br>".$tk6.$urun_usa_c["urun_bil_y3"]?><br> <?php echo $urun_ca_c["ulke_adi"]?> : <br><?php echo $tk1.$ca_by."<br>".$tk2.$urun_ca_c["urun_bil_y2"]."<br>".$tk3.$urun_ca_c["urun_bil_y3"]?></td><?php } ?>





														<?php if (strstr($_SESSION['filtre'],"urun_fiyat")) {?>  <td ><?php echo number_format($urun_usa_c["urun_fiyat"],2)." ".$urun_usa_c["para_birimi"];;  ?></td><?php } ?>

														<?php if (strstr($_SESSION['filtre'],"urun_fiyat")) {?>  <td ><?php echo number_format($urun_ca_c["urun_fiyat"],2)." ".$urun_ca_c["para_birimi"];?></td>
													<?php } ?>


													<?php if (strstr($_SESSION['filtre'],"urun_stok")) {?>  <td><?php echo $urun_usa_c["urun_stok"] ?></td><?php } ?>

													<?php if (strstr($_SESSION['filtre'],"urun_fba_d")) {?><td>
														<?php if ($urun_ca_c["urun_fba"]==1) {
															echo "FBA";
														} 
														else {
															echo "FBM";
														}
														?>
														</td><?php } ?>


														<?php if (strstr($_SESSION['filtre'],"satici_sayisi")) {?>  <td><?php echo $satici_sayisi ?></td><?php } ?>
														<?php if (strstr($_SESSION['filtre'],"marka")) {?>  <td><?php echo $urun_usa_c["urun_marka"] ?></td><?php } ?>
														<?php if (strstr($_SESSION['filtre'],"urun_cp")) {?>  <td><?php echo $urun_usa_c["urun_kupon"] ?></td><?php } ?>



													</tr>

												<?php } }?>
											</tbody>

										</table>
									</div>
									<?php  
									if ($_SESSION['filtre']==" ") {
										unset($_SESSION['filtre']);
									}
								}
								else{

									$urun_usa=$db->prepare("SELECT * from $nereden where urun_tarandi=? and kullanici_id=? order by urun_asin");
									$urun_usa->execute([1,$_SESSION['kullanici_id']]);
//CANADA
									$urun_ca=$db->prepare($sorfu_data);
									$urun_ca->execute([1,$_SESSION['kullanici_id']]);










									?>
									<html>
									<head>
										<meta charset="utf-8">

										<meta name="viewport" content="width=device-width, initial-scale=1.0">
										<title></title>


										<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
										<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
										<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
										<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
										<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
										<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
										<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">


									</head>
									<body>


										<div>

											<table id="example" class="table table-hover  dt-responsive nowrap" style="width:100%">
												<thead>



													<tr style="font-size: 10px" >
														<th rowspan="1"></th>
														<th rowspan="1">Görsel</th>

														<th rowspan="1">Açıklama</th>

														<th rowspan="1">Asin</th>
														<th rowspan="1">Puan</th>
														<th rowspan="1">Yorum</th>
														<th rowspan="1">BSR</th>
														<th rowspan="1">Fiyat</th>
														<th rowspan="1">H Fiyat</th>
														<th rowspan="1">Stok</th>
														<th rowspan="1">FBA/FBM</th>
														<th rowspan="1">Satıcı sayısı</th>
														<th rowspan="1">Marka</th>


													</tr>
												</thead>
												<tbody>
													<?php while ($urun_ca_c=$urun_ca->fetch(PDO::FETCH_ASSOC))

													{
														$urun_usa=$db->prepare("SELECT * from $nereden where urun_tarandi=? and kullanici_id=? and urun_asin=?".$_SESSION['sorgu_kupon'] );
														$urun_usa->execute([1,$_SESSION['kullanici_id'],$urun_ca_c["urun_asin"]]);

														while ($urun_usa_c=$urun_usa->fetch(PDO::FETCH_ASSOC)) {
															$böl_ad=explode(" ",$urun_ca_c["urun_ad"]);
															if (strstr($urun_usa_c["urun_bil_y1"],"See")) {
																$böl_bsr_usa=explode("See",$urun_usa_c["urun_bil_y1"]);
																$usa_by=$böl_bsr_usa[0];
															}
															$ca_by=$urun_ca_c["urun_bil_y1"];

															if (strstr($urun_ca_c["urun_bil_y1"],"See")) {
																$böl_bsr_ca=explode("See",$urun_ca_c["urun_bil_y1"]);
																$ca_by=$böl_bsr_ca[0];
															}
															$satici_sayisi=$urun_ca_c["satici_sayisi"];
															if ($urun_ca_c["satici_sayisi"]=="") {
																$satici_sayisi=1;
															}

															?>
															<tr style="font-size: 10px" >
																<td></td>
																<td><img src="<?php echo $urun_usa_c["urun_resim"]?>" width="30" height="30"></td>

																<td>
																	<?php echo $böl_ad[8]." ".$böl_ad[9]." ".$böl_ad[10]."<br> ".$böl_ad[11]." ".$böl_ad[12]."<br>".$böl_ad[13]." ".$böl_ad[14]."<br> ".$böl_ad[15]." ".$böl_ad[16]."<br>".$böl_ad[17].$böl_ad[18];
																?></td>


																<td><?php echo $urun_ca_c["urun_asin"];?></td>
																<td ><?php  echo number_format($urun_ca_c["urun_puan"],1);?>⭐️</td>
																<td><?php 
																$böl_yo=explode(".",$urun_ca_c["yorum_sayisi"]);
																if (substr($böl_yo[1],2)) {
																	echo number_format($urun_ca_c["yorum_sayisi"],3); } else {echo ceil($urun_ca_c["yorum_sayisi"]);}
																?>  <br>Yorum</td>
																<?php
																$böl_yo1=explode(".",$urun_ca_c["urun_bil_s1"]);
																if (substr($böl_yo1[1],2)) {
																	$tk1= number_format($urun_ca_c["urun_bil_s1"],3); } else {$tk1=ceil($urun_ca_c["urun_bil_s1"]);}


																	$böl_yo2=explode(".",$urun_ca_c["urun_bil_s2"]);
																	if (substr($böl_yo2[1],2)) {
																		$tk2= number_format($urun_ca_c["urun_bil_s2"],3); } else {$tk2=ceil($urun_ca_c["urun_bil_s2"]);}


																		$böl_yo3=explode(".",$urun_ca_c["urun_bil_s3"]);
																		if (substr($böl_yo3[1],2)) {
																			$tk3= number_format($urun_ca_c["urun_bil_s1"],3); } else {$tk3=ceil($urun_ca_c["urun_bil_s3"]);}


																			$böl_yo4=explode(".",$urun_usa_c["urun_bil_s1"]);
																			if (substr($böl_yo4[1],2)) {

																				$tk4= number_format($urun_usa_c["urun_bil_s1"],3); } else {$tk4=ceil($urun_usa_c["urun_bil_s1"]);}



																				$böl_yo5=explode(".",$urun_usa_c["urun_bil_s2"]);
																				if (substr($böl_yo5[1],2)) {
																					$tk5= number_format($urun_usa_c["urun_bil_s2"],3); } else {$tk5= ceil($urun_usa_c["urun_bil_s2"]);}



																					$böl_yo6=explode(".",$urun_usa_c["urun_bil_s3"]);
																					if (substr($böl_yo6[1],2)) {
																						$tk6= number_format($urun_usa_c["urun_bil_s3"],3); } else {$tk6= ceil($urun_usa_c["urun_bil_s3"]);}

																						?>


																						<td ><strong>USA</strong> : <br><?php echo $tk4.$usa_by."<br>".$tk5.$urun_usa_c["urun_bil_y2"]."<br>".$tk6.$urun_usa_c["urun_bil_y3"]?><br> <?php echo $urun_ca_c["ulke_adi"]?>: <br><?php echo $tk1.$ca_by."<br>".$tk2.$urun_ca_c["urun_bil_y2"]."<br>".$tk3.$urun_ca_c["urun_bil_y3"]?></td>




																						<td ><?php echo number_format($urun_usa_c["urun_fiyat"],2)." ".$urun_usa_c["para_birimi"];  ?></td>
																						<td ><?php echo number_format($urun_ca_c["urun_fiyat"],2)." ".$urun_ca_c["para_birimi"];  ?></td>
																						<td><?php echo $urun_usa_c["urun_stok"] ?></td>
																						<td>
																							<?php if ($urun_ca_c["urun_fba"]==1) {
																								echo "FBA";
	// code...
																							} 
																							else {
																								echo "FBM";
																							}
																							?>
																						</td>



																						<td><?php echo $satici_sayisi ?></td>
																						<td><?php echo $urun_usa_c["urun_marka"] ?></td>



																					</tr>

																				<?php }} ?>
																			</tbody>

																		</table>
																	</div>
																<?php } ?>
																
																
																
																<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
																<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>


																<script type="text/javascript">
																	$(document).ready(function () {

																		$('#example').DataTable({

																			"bDestroy": true,
																			"autoWidth": false,
																			"language": {
																				"url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
																			}


																		});



																	});




																</script>

															</body>
															</html>
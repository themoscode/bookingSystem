<!DOCTYPE html>
<html>

<head>
    <title>FLOWERPOWER iBUS</title>
    <meta name="description" content="website description">
    <meta name="keywords" content="website keywords, website keywords">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style.css"> 
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/slider.css">
    <link href="images/favicon.ico" type="image/x-icon" rel="icon">    
	<?php require ('php/page_controller.php');?>
</head>
	
<body>
 <noscript><div style="height:25px;background-color:#4682B4;text-align:center;color:white;font-size:14px;padding-top:3px;">Sie müssen JavaScript in Ihrem Browser aktivieren, um Online-Buchungen abschliessen zu können.</div></noscript>
    <br>
    
    <div id="main">
    
        <header>
            <div id="logo">
                <div id="logo_text">
                    <h1>
                        <a href="index.php"><img src="images/_logo.png"></a>
                    </h1>
                </div>
            </div>
            <nav>
                <div id="menu_container">
                    <ul class="sf-menu" id="nav">
                        <li class="<?php echo $home_class;?>"><a href="index.php">Home</a></li>
                        <li class="<?php echo $booking_class;?>"><a href="index.php?page=buchung">Buchung</a></li>
                        <li><a href="Liniennetz.html">Liniennetz</a></li>
                        <li><a href="Kundenservice.html">Kundenservice</a></li>
                        <li><a href="Uberuns.html">Über uns</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        
        <div id="site_content" >
            
             <div id="sidebar_container">
               <!-- BASKET / WARENKORB -->
            	<div style="display: none;" class="sidebar" id="sidebar-basket"></div>	
					
				<!--end basket-->
                <div class="sidebar" id="sidebar-book-box">
                    <h3>Reservierung / Buchung</h3>
                	<h4>einfach <span>und</span> schnell <span>über</span></h4>                    
                    
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <img src="images/tel.png">
                            </td>
                            <td>
                                <h4>24h Hotline</h4>
                                <h5>069 / 260 93 262</h5>
								<h5 id="tel">0172 / 45 40 087</h5>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <img src="images/sms.png">
                            </td>
                            <td>
                                <h4 id="sms">SMS</h4>
                                <h5>0172 / 45 40 087</h5>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <img id="imgmail" src="images/mail.png">
                            </td>
                            <td>
                                <a href="mailto:buchung@flowerpower-ibus.de">
                                <h4 id="mail">E-Mail</h4></a>
                            </td>
                        </tr>
                    </table>
                    
                </div>
            
                <div class="sidebar" id="sidebar-faq"><a href="FAQ.html">
                    <h3>FAQ</h3>
                    <h4>Häufig gestellte Fragen</h4></a>
                </div>
                
                <div class="sidebar" id="sidebar-mediaicons">
                    <p>
                        <a href="#"><img src="images/facebook.png" border="0"></a>
                        <a href="#"><img src="images/twitter.png" border="0"></a>
                        <a href="#"><img src="images/google.png" border="0"></a>
                        <a href="#"><img src="images/youtube.png" border="0"></a>
                        <a href="#"><img src="images/linkedin.png" border="0"></a>
                        <a href="#"><img src="images/skype.png" border="0"></a>
                        <a href="#"><img src="images/rss.png" border="0"></a>
                    </p>
                </div>
            </div>

       	<div id="content">
			<?php echo $book_text;?>
    		<!-- Booking -->
			
            <div id="booking">
                
            	<article id="main-search">
                	
                	<form id="search-form" >
                		  
                	  <div id="search-wrapper">
                		
                	  	<div id="header-form-row"><span>Welche Verbindung suchen Sie?</span></div>
                		    
                          <div class="form-row">
                          	
             				<div class="left-box">
							  <label for="from-city-search" id="label-from">Von</label>
							  <input type="text" id="from-city-search" name="val_from-city-search" 
							  value=""  maxlength="20" autocomplete="off">
							  <input type="hidden" id="from-city-id" name="val_from-city-id" 
							  value="0">
							  </div>	
																			
							<div class="right-box">
                			  <label id="label-there">Hin</label>
							  <input type="text" class="date" id="from-date" name="val_from-date" 
							  value="">
							</div>
							  
						  </div>
							
               			  <div class="form-row">
               				
               				<div class="left-box">
							  <label for="to-city-search" id="label-to">Nach</label>
						      <input type="text" id="to-city-search" name="val_to-city-search"
							  value=""  maxlength="20" autocomplete="off">
							  <input type="hidden" id="to-city-id" name="val_to-city-id" 
							  value="0">
							</div>
							   		
							<div class="right-box">	
							  <input type="checkbox" id="checkbox" name="val_checkbox" >		
							  <label id="label-back">Zurück</label>
							  <input type="text" class="date" id="back-date" name="val_back-date" value="">
							</div>
						   	  
						  </div>
								
						  <div class="form-row">
								
							<div class="quantity" id="quantity-adults">
							  <label for="from-city-search" class="quantity-title">Erwachsene</label>
							  <input type="number" name="val_adults" id="adults" class="quantity-input" 
							  value="1" min="1">
							</div>
								
							<div class="quantity" id="quantity-children">
							  <label for="from-city-search" class="quantity-title">Kinder / Ermäßigt</label>
							  <input type="number" name="val_children" id="children" class="quantity-input" 
							  value="0" min="0">
						  </div>
													
							  <input type="submit" id="startsearch" value="SUCHE VERBINDUNG">
						</div>
	
					  </div>
                	</form> 
                		
				</article>
                	
				<article id="to-search-result-area" style="display:none;">	
				</article>              	
						
				<article id="back-search-result-area" style="display:none;">	
				</article>    

            </div><!--end of booking-->
		    <!--booking form -->
			<div id="bookingClients" style="display:none;">
		
			    <form id="customerBookingForm">
			
					
		    	</form> 
				<div id="dont-forget" style="display:none;">
					[<span>NICHT VERGESSEN ANZUGEBEN:</span> Handynummer für Verspätungs-Benachrichtigung via SMS]<br>
					[<span>RESERVIERUNG:</span> Sie erhalten eine verbindliche Buchungsbestätigung in Form eines Online-							 Tickets, das Sie ausgedruckt zum Bus mitbringen müssen.]<br>
					[<span>BEZAHLUNG:</span> Die Bezahlung erfolgt vor Abfahrt am Bus.]<br><br>
					Haben Sie weitere Fragen? - Vielleicht ist Ihre Frage bereits in unseren <a href="FAQ.html">Fragen & Antworten (FAQ)</a> beantwortet. 						<br><br>
				</div>
			  </div><!--end bookingClients -->
				               
				
			 <!--booking form -->
                <table id="stations" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <img src="images/station-big.jpg">
                        </td>
                        <td>
                            <h4>Haltestellen</h4>
                            <h5>Berlin ZOB (Zentraler Omnibusbahnhof am Funkturm; erreichbar über S-Bahnhof<br>
                            ICC/Messe-Nord oder über U-Kaiserdamm)<br>Frankfurt am Main Hauptbahnhof 
                            (Südausgang, Mannheimer Str.)<br>Wiesbaden Hauptbahnhof, Gartenfeldstraße 
                            (Bussteig 2 vor dem Delta-Haus)</h5>
                        </td>
                    </tr>
                </table>
                <br>
                
            </div><!--end content-->

        </div> <!--end site-content-->

        <footer>
            <div id="footerContainer">
                <p id="offtopic">
                	<a href="AGB.html">AGB</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <a href="Datenschutz.html">Datenschutz</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <a href="Job.html">Jobs</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <a href="Kontakt.html">Kontakt</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
               		<a href="Impressum.html">Impressum</a>
                </p>
                <p>© 2014 flowerpower ibus GmbH. Alle Rechte vorbehalten</p>
            </div>
        </footer>
    </div>
	
    <br>
  
    	<!-- javascript at the bottom for fast page loading -->
    	<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
    	<script type="text/javascript" src="js/jquery-ui-1.10.4.custom.js"></script>
    	<script type="text/javascript" src="js/jquery.slides.min.js"></script>
		<script type="text/javascript" src="js/class_slideshow.js"></script>
		<script type="text/javascript" src="js/class_datepicker.js"></script>
		<script type="text/javascript" src="js/class_search.js"></script>
		<script type="text/javascript" src="js/class_autocomplete.js"></script>
		<script type="text/javascript" src="js/class_basket.js"></script>
		<script type="text/javascript" src="js/class_booking.js"></script>
		<script type="text/javascript" src="js/class_session.js"></script>
		<script type="text/javascript" src="js/class_engine.js"></script>
		<script type="text/javascript" src="js/main_php.js"></script>
		
  </body>
</html>
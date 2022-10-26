<?php

$processed = $this->getProcessed();

?>
<script language="JavaScript" src="./javascript/calonlydays.js"></script>

<FORM action=<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL); ?> class="kt-form" id="myForm" method="post" name="myForm">
	<div class="kt-portlet__body">
		<div class="form-group">
       
			<INPUT type="hidden" name="form_action" value="add">
			<INPUT type="hidden" name="wh" value="<?php echo $wh; ?>">

			<?php
			  if ($this->FG_CSRF_STATUS == true) {
			?>
			  <INPUT type="hidden" name="<?php echo $this->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $this->FG_FORM_UNIQID; ?>" />
			  <INPUT type="hidden" name="<?php echo $this->FG_CSRF_FIELD ?>" value="<?php echo $this->FG_CSRF_TOKEN; ?>" />
			<?php
			  }
			?>

			<?php
                if (!empty($this->FG_QUERY_ADITION_HIDDEN_FIELDS)) 
				{
                    $split_hidden_fields = preg_split("/,/",trim($this->FG_QUERY_ADITION_HIDDEN_FIELDS));
                    //print_r($split_hidden_fields);
                    $split_hidden_fields_value = preg_split("/,/",trim($this->FG_QUERY_ADITION_HIDDEN_VALUE));
                    for ($cur_hidden=0;$cur_hidden<count($split_hidden_fields);$cur_hidden++) {
                        echo "<INPUT type=\"hidden\" name=\"".trim($split_hidden_fields[$cur_hidden])."\" value=\"".trim($split_hidden_fields_value[$cur_hidden])."\">\n";
                    }
                }
                if (!empty($this->FG_ADITION_HIDDEN_PARAM)) 
				{
                    $split_hidden_fields = preg_split("/,/",trim($this->FG_ADITION_HIDDEN_PARAM));
                    $split_hidden_fields_value = preg_split("/,/",trim($this->FG_ADITION_HIDDEN_PARAM_VALUE));
                    for ($cur_hidden=0;$cur_hidden<count($split_hidden_fields);$cur_hidden++) 
					{
                        echo "<INPUT type=\"hidden\" name=\"".trim($split_hidden_fields[$cur_hidden])."\" value=\"".trim($split_hidden_fields_value[$cur_hidden])."\">\n";
                    }
                }
                ?>
				
				<?php //print_r($atmenu);?>
				<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
				<div class="uk-grid">
                             <div class="uk-width-1-1">
                                <div class="uk-grid uk-grid-width-1-1 uk-grid-width-large-1-2" >
    <?php
        $grid=false;
        for ($i=0;$i<$this->FG_NB_TABLE_ADITION;$i++) {
           
            
              $pos = strpos($this->FG_TABLE_ADITION[$i][14], ":");
            
            if (strlen($this->FG_TABLE_ADITION[$i][16])>1 && strtoupper ($this->FG_TABLE_ADITION[$i][3])!=("HAS_MANY")) { $grid=true;
                // echo '<h3 class="full_width_in_card heading_c">'.$this->FG_TABLE_EDITION[$i][16].'</h3>';
                 
            }
            if(!$grid && $i!=0)
            {
                ?>
               
                <?php
            }
            
            if($grid){
            ?>
                
            <?php
            }
            ?>
               
                    
			<?php  
				if (!$pos) 
				{ 
					$grid=false;
			?>           
				   
			<div> 
				<?php 
					if (!$this-> FG_fit_expression[$i]  &&  isset($this-> FG_fit_expression[$i]) ) 
					{
				?>
                          
				<div class="form-group">
					<!--<span class="uk-input-group-addon">
					<i class="md-list-addon-icon material-icons">&#xE158;</i>
					</span>-->
					<label class="col-12 col-form-label" for="<?php echo $this->FG_TABLE_ADITION[$i][1]?>"  <?php echo $this->FG_TABLE_ADITION[$i][4]?>>
						<?php echo  $this->FG_TABLE_ADITION[$i][0]?>
					</label>
                            
                         
                    <?php 
						} 
					else 
						{ 
					?>
                        
					<div class="uk-input-group">
						<!--<span class="uk-input-group-addon">
						<i class="md-list-addon-icon material-icons">&#xE158;</i>
						</span>-->
						<label class="col-12 col-form-label" for="<?php echo $this->FG_TABLE_ADITION[$i][1]?>"  <?php echo $this->FG_TABLE_ADITION[$i][4]?>>
							<?php echo $this->FG_TABLE_ADITION[$i][0]?>
						</label>
                                                    
						<?php 
						} 
						?>

						<?php
							if ($this->FG_DEBUG == 1) print($this->FG_TABLE_ADITION[$i][3]);
								if (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="INPUT") 
								{	
						?>             
                        <INPUT class="form-control" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php echo $processed[$this->FG_TABLE_ADITION[$i][1]];?>">
					</div>
				</div>
				<?php
								} 
								elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="LABEL")
								{
				?>
				<?php echo $this->FG_TABLE_ADITION[$i][4]?>
                <?php
								} 
								elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="POPUPVALUE") 
								{
                ?>
                    
				<INPUT class="form-control" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="
				<?php
					if ($this->VALID_SQL_REG_EXP) 
					{
						echo stripslashes($list[0][$i]);
					} 
					else 
					{ 
						echo $processed[$this->FG_TABLE_ADITION[$i][1]]; 
					}?>">
				<a href="#" onclick="window.open('<?php echo $this->FG_TABLE_ADITION[$i][12]?>popup_formname=myForm&popup_fieldname=<?php echo $this->FG_TABLE_ADITION[$i][1]?>' <?php echo $this->FG_TABLE_ADITION[$i][13]?>);">
					<i class="material-icons" style="color: orange;display: inline">&#xE154;</i>
				</a>
				<!--CAPTCHA IMAGE CODE START HERE-->
                <?php
								} 
								elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="CAPTCHAIMAGE") 
								{
                ?>
									<span><img src="./captcha/captcha.php" ></span>
									<INPUT class="md-input" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  
										<?php 	
											echo $this->FG_TABLE_ADITION[$i][4]
										?> 
										value="<?php echo $processed[$this->FG_TABLE_ADITION[$i][1]];?>">
									<span><?php echo gettext("Enter code from above picture here.");?></span>
			</div> 
		</div>  

            <!--CAPTCHA IMAGE CODE END HERE-->

                <?php
                    } 
                    elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="POPUPVALUETIME") 
                    {
                ?>
                    
                    <INPUT class="md-input" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php if ($this->VALID_SQL_REG_EXP) 
                    { 
                        echo stripslashes($list[0][$i]); 
                    } else { echo $processed[$this->FG_TABLE_ADITION[$i][1]]; }?>">
                    
                    <a href="#" onclick="window.open('<?php echo $this->FG_TABLE_ADITION[$i][14]?>formname=myForm&fieldname=<?php echo $this->FG_TABLE_ADITION[$i][1]?>' <?php echo $this->FG_TABLE_ADITION[$i][14]?>);">
                    <i class="material-icons" style="color: orange;display: inline">&#xE154;</i>
                    </a>
                    </div>
                    </div>
                <?php
                    } 
                    elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="POPUPDATETIME") 
                    {
                ?>
                        
                       <INPUT class="md-input" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php if ($this->VALID_SQL_REG_EXP) { echo stripslashes($list[0][$i]); } else { echo $processed[$this->FG_TABLE_ADITION[$i][1]]; }?>">
                       
                    <a href="javascript:cal<?php echo $this->FG_TABLE_ADITION[$i][1]?>.popup();">
                    <i class="material-icons" style="color: orange;display: inline"  title="Click Here to Pick up the date" >&#xE8DF;</i></a>
		</div>
                    <script language="JavaScript">
                    <!-- // create calendar object(s) just after form tag closed
                    // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
                    // note: you can have as many calendar objects as you need for your application
                    var cal<?php echo $this->FG_TABLE_ADITION[$i][1]?> = new calendaronlyminutes(document.forms['myForm'].elements['<?php echo $this->FG_TABLE_ADITION[$i][1]?>']);
                    cal<?php echo $this->FG_TABLE_ADITION[$i][1]?>.year_scroll = false;
                    cal<?php echo $this->FG_TABLE_ADITION[$i][1]?>.time_comp = true;
                    cal<?php echo $this->FG_TABLE_ADITION[$i][1]?>.formatpgsql = true;
                    //-->                                                                                         3
                    </script>
                <?php
                    } 
                    elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="TEXTAREA") 
                    {
                ?>
                        
                        <TEXTAREA class="md-input selecize_init" style="overflow-x: hidden; word-wrap: break-word;height:90px" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?> <?php echo $this->FG_TABLE_ADITION[$i][4]?>>
                        <?php echo $processed[$this->FG_TABLE_ADITION[$i][1]];?>
                        </TEXTAREA>  
                        </div>
                       </div> 
                <?php
                    } 
                    elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="SELECT") 
                    {
                        if ($this->FG_DEBUG == 1) { echo "<br> TYPE DE SELECT :".$this->FG_TABLE_ADITION[$i][7];}
                        if (strtoupper ($this->FG_TABLE_ADITION[$i][7])=="SQL") {

                            $instance_sub_table = new Table($this->FG_TABLE_ADITION[$i][8], $this->FG_TABLE_ADITION[$i][9]);
                            $select_list = $instance_sub_table -> Get_list ($this->DBHandle, $this->FG_TABLE_ADITION[$i][10], null, null, null, null, null, null);
                            if ($this->FG_DEBUG >= 2) { echo "<br>"; print_r($select_list);}
                        } elseif (strtoupper ($this->FG_TABLE_ADITION[$i][7])=="LIST") {
                            $select_list = $this->FG_TABLE_ADITION[$i][11];
                        }
                ?>
                        
                       <SELECT  name='<?php echo $this->FG_TABLE_ADITION[$i][1]?><?php if (strpos($this->FG_TABLE_ADITION[$i][4], "multiple")) echo "[]";?>'  <?php echo $this->FG_TABLE_ADITION[$i][4]?> class="md-input" data-uk-tooltip="{pos:'top'}">
                <?php
                        echo ($this->FG_TABLE_ADITION[$i][15]);
                        if (strlen($this->FG_TABLE_ADITION[$i][6])>0) {
                ?>
                <option value="-1"><?php echo $this->FG_TABLE_ADITION[$i][6]?></option>
                <?php  }
                            if (count($select_list)>0) {
                                $select_number=0;
                                  foreach ($select_list as $select_recordset) {
                                    $select_number++;
                                       if ($this->FG_TABLE_ADITION[$i][12] != "") {
                                        $value_display = $this->FG_TABLE_ADITION[$i][12];
                                        $nb_recor_k = count($select_recordset);
                                        for ($k=1;$k<=$nb_recor_k;$k++) {
                                            $value_display  = str_replace("%$k", $select_recordset[$k-1], $value_display );
                                        }
                                    } else {
                                        $value_display = $select_recordset[0];
                                    }
                ?>
                <OPTION  value='<?php echo $select_recordset[1]?>'
                <?php
                                    if ($this->FG_TABLE_ADITION[$i][2] == $select_recordset[1]) echo "selected";

                                    // CLOSE THE <OPTION
                                    echo '> ';
                                    echo $value_display.'</OPTION>';

                                 } // END_FOREACH
                             } else {
                                  echo gettext("No data found !!!");
                             }//END_IF
                ?>
                    </SELECT>
                        </div>
                    </div>
                <?php
                        } 
                    elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="RADIOBUTTON") 
                    {
                            $radio_table = preg_split("/,/",trim($this->FG_TABLE_ADITION[$i][10]));
                            foreach ($radio_table as $radio_instance) {
                                $radio_composant = preg_split("/:/",$radio_instance);
                                echo '<span class="icheck-inline">';
                               
                                echo ' <input data-md-icheck type="radio" name="'.$this->FG_TABLE_ADITION[$i][1].'" value="'.$radio_composant[1].'" ';
                                // TODO just a temporary and quick hack please review $VALID_SQL_REG_EXP
                                if ($processed[$this->FG_TABLE_ADITION[$i][1]]==$radio_composant[1]) {
                                    echo "checked";
                                } elseif ($VALID_SQL_REG_EXP) {
                                    $know_is_checked = stripslashes($list[0][$i]);
                                } else {
                                    $know_is_checked = $this -> FG_TABLE_ADITION[$i][2];
                                }

                                if ($know_is_checked==$radio_composant[1]) {
                                    echo "checked";
                                }
                                echo ">";
                                echo '<label for="'.$this->FG_TABLE_ADITION[$i][1].'" class="inline-label">'.$radio_composant[0].'</label>
                                    </span>';
                            }
                            ?>
                            
                            <?php
                        }//END_IF (RADIOBUTTON)
                ?>
                    <br>
                 <?php
                        if (!$this-> FG_fit_expression[$i]  &&  isset($this-> FG_fit_expression[$i]) ) {
                            echo "<br><i style='font-size:12px'>".$this->FG_TABLE_ADITION[$i][6]." ".$this->FG_regular[$this->FG_TABLE_ADITION[$i][5]][1]."</i>";
                        }
                 ?>     
                <?php
                        
                        if (strlen($this->FG_TABLE_COMMENT[$i])>0) {  ?><br/><i style='font-size:12px'><?php  echo $this->FG_TABLE_COMMENT[$i];?></i><br>  <?php  } ?>
                        
                      
                <?php       
                          
                             
                        }
                        ?>
                       
                       
                
            
       <?php
        }//END_FOR
            
        ?>
        </div>  <!--data-uk-grid-margin-->
        </div>  <!--uk width 11 end-->
        </div> <!--uk-grid end-->   
        
      <TABLE cellspacing="0" class="editform_table8" style="width:80%;margin-top:10px">
        <tr>
            <td width="50%" class="text_azul" style="text-align: right;"><span class="tableBodyRight"><?php echo $this->FG_BUTTON_ADITION_BOTTOM_TEXT?></span></td>
                        <td width="50%" align="right" valign="top" class="text">
                <a href="#" onClick="javascript:document.getElementById('myForm').submit();" title="<?php echo gettext("Create a new ");?><?php echo $this->FG_INSTANCE_NAME?>" alt="<?php echo gettext("Create a new ");?> <?php echo $this->FG_INSTANCE_NAME?>" border=0 hspace=0 id="submit4" name="submit2" class="md-btn md-btn-primary md-btn-small ">
                <?php echo $this->FG_ADD_PAGE_CONFIRM_BUTTON; ?> </a>
               
                <!--<INPUT  class="md-btn md-btn-primary md-btn-small" title="<?php echo gettext("Create a new ");?><?php echo $this->FG_INSTANCE_NAME?>" alt="<?php echo gettext("Create a new ");?> <?php echo $this->FG_INSTANCE_NAME?>" border=0 hspace=0 id=submit4 name=submit2 src="<?php echo $this->FG_BUTTON_ADITION_SRC?>" type="submit">-->
                
            </td>
        </tr>
      </TABLE> </div></div>     
    </FORM>

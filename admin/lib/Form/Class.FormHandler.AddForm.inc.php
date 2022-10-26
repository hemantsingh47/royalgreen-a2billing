<?php

$processed = $this->getProcessed();

?>
<script language="JavaScript" src="./javascript/calonlydays.js"></script>
 
<div class="kt-portlet" >

    
    <FORM action=<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL); ?> id="myForm" method="post" name="myForm" class="kt-form">
        <INPUT type="hidden" name="form_action" value="add">
        <INPUT type="hidden" name="wh" value="<?php echo $wh; ?>">
        <?php
            if ($this->FG_CSRF_STATUS == true) 
            {
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
                for ($cur_hidden=0;$cur_hidden<count($split_hidden_fields);$cur_hidden++) 
                {
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

<div class="form-group " style="margin:5px">
<div class="row">

<div class="col-md-12">
         
        <?php
            $ki = FALSE;
            for ($i=0;$i<$this->FG_NB_TABLE_ADITION;$i++) 
            {
                $pos = strpos($this->FG_TABLE_ADITION[$i][14], ":");
                if (strlen($this->FG_TABLE_ADITION[$i][16])>1 && strtoupper ($this->FG_TABLE_ADITION[$i][3])!=("HAS_MANY")) 
                {
                    $ki =  TRUE;
                    ?>
                    
                    </div>
              </div>
              </div>
                    
                    <div class="col-lg-12 " style="margin:0 auto;">
                    <div class="kt-portlet" style="border: 1px solid rgba(88, 103, 221, 0.25); padding:10px;">
					
					<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					 <h3 class="heading_b uk-margin-bottom">
						              <?php echo $this->FG_TABLE_EDITION[$i][16]; ?>				            
						            </h3>
				</div>
			</div>
					
					
                       <!-- <div class=""> <span class="icon"> <i class="icon-align-justify"></i> </span>
                          <h3 class="kt-portlet__head-title" style="border-bottom:0px solid;"><?php echo $this->FG_TABLE_EDITION[$i][16]; ?></h3>
                        </div>-->
                    <div class="kt-portlet__head-label">
                <?php
                }
                
                if (!$pos) 
                {
                ?> 
                
            <div class="control-group" style="border-bottom:0px; clear:both;">
           <?php if (!$this-> FG_fit_expression[$i]  &&  isset($this-> FG_fit_expression[$i]) ) 
            {
            ?>
                 
                <label class="control-label-ag"  for="<?php echo $this->FG_TABLE_ADITION[$i][1]?>"  <?php echo ucfirst($this->FG_TABLE_ADITION[$i][4])?>><?php echo  $this->FG_TABLE_ADITION[$i][0]?></label>
            <?php 
            } 
            else
            { 
            ?>
               
               <label class="control-label-ag"  for="<?php echo $this->FG_TABLE_ADITION[$i][1]?>"  <?php echo ucfirst($this->FG_TABLE_ADITION[$i][4])?>><?php echo $this->FG_TABLE_ADITION[$i][0]?></label>
               
            <?php 
            } 
            ?>
            <div class="controls" style="width:75%;">
            <?php
            if ($this->FG_DEBUG == 1) print($this->FG_TABLE_ADITION[$i][3]);
            if (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="INPUT") 
            {
        ?>             
                     <INPUT type="text" class="form-control" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php echo $processed[$this->FG_TABLE_ADITION[$i][1]];?>">
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
            
            <INPUT type="text" style="width:90%" class="form-control" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php
            if ($this->VALID_SQL_REG_EXP) {
                    echo stripslashes($list[0][$i]);
                } else { echo $processed[$this->FG_TABLE_ADITION[$i][1]]; }?>">
            <a href="#" onclick="window.open('<?php echo $this->FG_TABLE_ADITION[$i][12]?>popup_formname=myForm&popup_fieldname=<?php echo $this->FG_TABLE_ADITION[$i][1]?>' <?php echo $this->FG_TABLE_ADITION[$i][13]?>);"><i class="flaticon2-fast-next" ></i></a>
    <!--CAPTCHA IMAGE CODE START HERE-->
        <?php
            } 
            elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="CAPTCHAIMAGE") 
            {
        ?>
            <table cellpadding="2" cellspacing="0" border="0" width="100%">
                <tr>
                    <td> <img src="./captcha/captcha.php" ></td>
                </tr>
                <tr>
                <td>
                
                <INPUT type="text" class="form-control" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php echo $processed[$this->FG_TABLE_ADITION[$i][1]];?>"> Enter code from above picture here.
                </td>
                </tr>
                </table> 

    <!--CAPTCHA IMAGE CODE END HERE-->

        <?php
            } 
            elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="POPUPVALUETIME") 
            {
        ?>
            
            <INPUT type="text" class="form-control" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php if ($this->VALID_SQL_REG_EXP) { echo stripslashes($list[0][$i]); } else { echo $processed[$this->FG_TABLE_ADITION[$i][1]]; }?>">
            <a href="#" onclick="window.open('<?php echo $this->FG_TABLE_ADITION[$i][14]?>formname=myForm&fieldname=<?php echo $this->FG_TABLE_ADITION[$i][1]?>' <?php echo $this->FG_TABLE_ADITION[$i][14]?>);"><img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/></a>
        <?php
            } 
            elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="POPUPDATETIME") 
            {
        ?>
              <INPUT type="text" class="form-control" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?>  <?php echo $this->FG_TABLE_ADITION[$i][4]?> value="<?php if ($this->VALID_SQL_REG_EXP) { echo stripslashes($list[0][$i]); } else { echo $processed[$this->FG_TABLE_ADITION[$i][1]]; }?>">
            <a href="javascript:cal<?php echo $this->FG_TABLE_ADITION[$i][1]?>.popup();"><img src="<?php echo Images_Path_Main;?>/cal.gif" width="16" height="16" border="0" title="Click Here to Pick up the date" alt="Click Here to Pick up the date"></a>
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
            <TEXTAREA class="form-control" style="overflow-x: hidden; word-wrap: break-word;height:120px" name=<?php echo $this->FG_TABLE_ADITION[$i][1]?> <?php echo $this->FG_TABLE_ADITION[$i][4]?>><?php echo $processed[$this->FG_TABLE_ADITION[$i][1]];?></TEXTAREA>  
                
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
                
               <SELECT style="width: 60%;height: 100%;" class="form-control11"   name='<?php echo $this->FG_TABLE_ADITION[$i][1]?><?php if (strpos($this->FG_TABLE_ADITION[$i][4], "multiple")) echo "[]";?>' <?php if (strpos($this->FG_TABLE_ADITION[$i][4], "multiple")){ echo ""; }else { echo ""; } ?>    <?php echo $this->FG_TABLE_ADITION[$i][4]?> >
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
                
        <?php
                } 
            elseif (strtoupper ($this->FG_TABLE_ADITION[$i][3])=="RADIOBUTTON") 
            {
             echo '<div class=""><div class="" style="display:-webkit-inline-box">';      
                    $br = TRUE;
                    $radio_table = preg_split("/,/",trim($this->FG_TABLE_ADITION[$i][10]));
                    foreach ($radio_table as $radio_instance) {
                        $radio_composant = preg_split("/:/",$radio_instance);
                        if($br)
                        {
                           
                            $br = FALSE;
                        }
                        echo '&nbsp;<label >'.$radio_composant[0].'';
                        echo '&nbsp;<input  type="radio" name="'.$this->FG_TABLE_ADITION[$i][1].'" value="'.$radio_composant[1].'" ';
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
                        echo "></label>";
                    }
                    ?>
                    
                    <?php
            echo "</div></div>";    
            }//END_IF (RADIOBUTTON)
        ?>
            
         <?php
            if (!$this-> FG_fit_expression[$i]  &&  isset($this-> FG_fit_expression[$i]) ) 
            {
                echo '<span class="liens" style="text-align:left;color:#ff0000;">';
                echo "<br>".$this->FG_TABLE_ADITION[$i][6]." ".$this->FG_regular[$this->FG_TABLE_ADITION[$i][5]][1];
                echo '</span>';
            }
         ?>    
          
          <?php
            if (strlen($this->FG_TABLE_COMMENT[$i])>0) 
            {  
                echo "<div style='text-align:left'><i style='font-size:12px;'>".$this->FG_TABLE_COMMENT[$i].' </i></div><br> ';
            } 
          ?>
           </div>     
         </div>
         
        <?php       
                 
                }
                 if($ki)
          {
              ?>
             
              <?php
          }       
            $ki = FALSE; 
            }

            //END_FOR

        ?>
       <!--</div>-->
       
       <div class="">
            <div >
                <div class="md-card" style="clear:both;">
                    <div class="md-card-content " style="padding: 8px; margin: 1px; text-align: right;">
                        <!-- <?php echo $this->FG_BUTTON_ADITION_BOTTOM_TEXT?> -->
                        <a href="#" onClick="javascript:document.getElementById('myForm').submit();" title="<?php echo gettext("Create a new ");?><?php echo $this->FG_INSTANCE_NAME?>" alt="<?php echo gettext("Create a new ");?> <?php echo $this->FG_INSTANCE_NAME?>" border=0 hspace=0 id="submit4" name="submit2" class="btn btn-primary btn-small ">
                <?php echo $this->FG_ADD_PAGE_CONFIRM_BUTTON; ?> </a>
                    </div>
                </div>
            </div>
        </div> 
       
               
                <!--<INPUT  class="md-btn md-btn-primary md-btn-small" title="<?php echo gettext("Create a new ");?><?php echo $this->FG_INSTANCE_NAME?>" alt="<?php echo gettext("Create a new ");?> <?php echo $this->FG_INSTANCE_NAME?>" border=0 hspace=0 id=submit4 name=submit2 src="<?php echo $this->FG_BUTTON_ADITION_SRC?>" type="submit">-->
                
           



           
           </FORM>
     </div>
	

</div>
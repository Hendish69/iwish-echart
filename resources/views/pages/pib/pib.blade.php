@extends('layouts.app')

@section('template_title')
    PIB
@endsection

@section('head')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- HEADER -->
                <div id="base" class="nk-content-wrapper">
                    <div class="panel panel-default">
                        <a class="btn" onclick="showmodal()"><h6>:: Pre-Flight Information Bulletins (PIB) ::</h6></a>
                        <div class="card-inner">
                            <div class="row">
                                <div class="form-check col-md-3">
                                    <input class="form-check-input" onclick="selectradio()" checked="cheked" name="pib" type="radio" id="ArptNotam">
                                    <label class="form-check-label" for="ArptNotam">Aerodrome</label>
                                </div>
                                <div class="form-check col-md-3">
                                    <input class="form-check-input" onclick="selectradio()" type="radio" name="pib" id="FIRNotam">
                                    <label class="form-check-label" for="FIRNotam">FIR Airspace</label>
                                </div>
                            </div>
                        </div>
                        <div id="aixmtitle" style="visibility:hidden">
                            <div class="modal-dialog-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-footer bg-gray">
                                        <h6 class="modal-title text-black-50">:: Pre-Flight Information Bulletins (PIB) ::</h6>
                                        <a onclick="showmodal()" class="close" data-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <div class="field field-name-field-basic-body field-type-text-long field-label-hidden">
                                            <div class="field-items">
                                                <div class="field-item even">
                                                    <ul>
                                                        <li align="center">REPUBLIC OF INDONESIA</li>
                                                        <li align="center">AIRNAV INDONESIA</li>
                                                        <li align="center">AERONAUTICAL INFORMATION SERVICE CENTER</li>
                                                        <li align="center">Gedung AirNav Indonesia, Jl. Ir. H Juanda Tangerang 15121</li>
                                                        <li align="center">Email: ais.center@airnavindonesia.co.id</li>
                                                        <li class=" mt-1" align="center"><strong><h4>:: DAILY PRE-FLIGHT INFORMATION BULLETIN ::</h4></strong></li>
                                                        <li class=" mt-1" align="center">Pilot or Operator also check current aeronautical information available at AIS Regional Office:</li>
                                                        <li align="center">Jakarta / 081282478117 briefingofficeshia@gmail.com | Makassar / 08114612017aismakassar@gmail.com
                                                                Surabaya/ 08113210034 aisjuanda@yahoo.co.id | Denpasar / 087760280178 ais.denpasar1@gmail.com
                                                                Medan / 082276690123 ais_kno@yahoo.com | Palembang / 082186273613 pia.palembang01@gmail.com
                                                                Balikpapan / 08122227019 pia.wilayahbalikpapan@gmail.com | Manado / 082187482976 manadoais@gmail.com
                                                                Sentani / 0811483617 ais.sentanijayapura@gmail.com | Sorong / 082248461446 aissorong@gmail.com
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <span class="sub-text">&copy; 2020 IWISHIndonesia.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- <div class="row" style="padding:50px;">
                        <form method="post">
                            <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="validity_from">Validity From:</label>
                                    <input type="date" class="form-control hasDatepicker" id="validity_from" name="validity_from">
                                </div>
                                <div class="form-group">
                                    <label for="validity_from">Validity To:</label>
                                    <input type="date" class="form-control hasDatepicker" id="validity_to" name="validity_to">
                                </div>
                                <label for="code_list">Code List</label>
                                <div class="form-group">
                                    <select class="form-control select2-hidden-accessible" id="code_list" name="code_list[]" multiple="" style="width:100%" data-select2-id="code_list" tabindex="-1" aria-hidden="true"></select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="1" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    <span>Q </span><select id="code_list_2_3" name="code_list_2_3" style="width: 45%" data-select2-id="code_list_2_3" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                                                    <option value="AA" data-select2-id="3">AA - MINIMUM ALTITUDE</option>
                                                                    <option value="AC">AC - CLASS B, C, D OR E SURFACE AREA (ICAO-CONTROL ZONE)</option>
                                                                    <option value="AD">AD - ADIZ</option>
                                                                    <option value="AE">AE - CONTROL AREA</option>
                                                                    <option value="AF">AF - FLIGHT INFORMATION REGION (FIR)</option>
                                                                    <option value="AG">AG - GENERAL FACILITY</option>
                                                                    <option value="AH">AH - UPPER CONTROL AREA</option>
                                                                    <option value="AL">AL - MINIMUM USABLE FLIGHT LEVEL</option>
                                                                    <option value="AN">AN - AIR NAVIGATION ROUTE</option>
                                                                    <option value="AO">AO - OCEANIC CONTROL ZONE (OCA)</option>
                                                                    <option value="AP">AP - REPORTING POINT</option>
                                                                    <option value="AR">AR - ATS ROUTE</option>
                                                                    <option value="AT">AT - TERMINAL CONTROL AREA (TMA)</option>
                                                                    <option value="AU">AU - UPPER FLIGHT INFORMATION REGION</option>
                                                                    <option value="AV">AV - UPPER ADVISORY AREA</option>
                                                                    <option value="AX">AX - INTERSECTION</option>
                                                                    <option value="AZ">AZ - AERODROME TRAFFIC ZONE (ATZ)</option>
                                                                    <option value="CA">CA - AIR/GROUND FACILITY</option>
                                                                    <option value="CB">CB - AUTOMATIC DEPENDENT SURVEILLANCE - BROADCAST</option>
                                                                    <option value="CC">CC - AUTOMATIC DEPENDENT SURVEILLANCE - CONTRACT</option>
                                                                    <option value="CD">CD - CONTROLLER-PILOT DATA LINK COMMUNICATIONS</option>
                                                                    <option value="CE">CE - ENROUTE SURVELLENCE RADAR</option>
                                                                    <option value="CG">CG - GROUND CONTROLLED APPROACH SYSTEM (GCA)</option>
                                                                    <option value="CL">CL - SELECTIVE CALLING SYSTEM</option>
                                                                    <option value="CM">CM - SURFACE MOVEMENT RADAR</option>
                                                                    <option value="CP">CP - PAR</option>
                                                                    <option value="CR">CR - SURVEILLANCE RADAR ELEMENT OF PAR SYSTEM</option>
                                                                    <option value="CS">CS - SECONDARY SURVEILLANCE RADAR</option>
                                                                    <option value="CT">CT - TERMINAL AREA SURVEILLANCE RADAR</option>
                                                                    <option value="FA">FA - AERODROME</option>
                                                                    <option value="FB">FB - BRAKING ACTION MEASUREMENT EQUIPMENT</option>
                                                                    <option value="FC">FC - CEILING MEASUREMENT EQUIPMENT</option>
                                                                    <option value="FD">FD - DOCKING SYSTEM</option>
                                                                    <option value="FE">FE - OXYGEN</option>
                                                                    <option value="FF">FF - FIRE FIGHTING AND RESCUE</option>
                                                                    <option value="FG">FG - GROUND MOVEMENT CONTROL</option>
                                                                    <option value="FH">FH - HELICOPTER ALIGHTING AREA/PLATFORM</option>
                                                                    <option value="FI">FI - AIRCRAFT DE-ICING</option>
                                                                    <option value="FJ">FJ - OILS</option>
                                                                    <option value="FL">FL - LANDING DIRECTION INDICATOR</option>
                                                                    <option value="FM">FM - METEOROLOGICAL SERVICE</option>
                                                                    <option value="FO">FO - FOG DISPERSAL SYSTEM</option>
                                                                    <option value="FP">FP - HELIPORT</option>
                                                                    <option value="FS">FS - SNOW REMOVAL EQUIPMENT</option>
                                                                    <option value="FT">FT - TRANSMISSOMETER</option>
                                                                    <option value="FU">FU - FUEL AVAILABILITY</option>
                                                                    <option value="FW">FW - WIND DIRECTION INDICATOR</option>
                                                                    <option value="FZ">FZ - CUSTOMS</option>
                                                                    <option value="GB">GB - OPTICAL LANDING SYSTEM</option>
                                                                    <option value="GC">GC - TRANSIENT MAINTENANCE</option>
                                                                    <option value="GD">GD - STARTER UNIT</option>
                                                                    <option value="GE">GE - SOAP</option>
                                                                    <option value="GF">GF - DEMINERALIZED WATER</option>
                                                                    <option value="GG">GG - OXYGEN</option>
                                                                    <option value="GH">GH - OIL</option>
                                                                    <option value="GI">GI - DRAG CHUTES</option>
                                                                    <option value="GJ">GJ - ASR</option>
                                                                    <option value="GK">GK - PRECISION APPROACH LANDING SYSTEM</option>
                                                                    <option value="GL">GL - FACSFAC</option>
                                                                    <option value="GM">GM - FIRING RANGE</option>
                                                                    <option value="GN">GN - NIGHT VISION GOGGLE (NVG) OPERATIONS</option>
                                                                    <option value="GO">GO - WARNING AREA</option>
                                                                    <option value="GP">GP - ARRESTING GEAR MARKERS (AGM)</option>
                                                                    <option value="GQ">GQ - PULSATING/STEADY VISUAL APPROACH SLOPE INDICATOR</option>
                                                                    <option value="GR">GR - DIVERSE DEPARTURE</option>
                                                                    <option value="GS">GS - NITROGEN</option>
                                                                    <option value="GT">GT - FR TAKE-OFF MINIMUMS AND DEPARTURE PROCEDURES</option>
                                                                    <option value="GU">GU - DE-ICE</option>
                                                                    <option value="GV">GV - CLEAR ZONE</option>
                                                                    <option value="GX">GX - RUNWAY DISTANCE MARKERS (RDM)</option>
                                                                    <option value="GY">GY - HELO PAD</option>
                                                                    <option value="GZ">GZ - BASE OPERATIONS</option>
                                                                    <option value="IC">IC - ILS</option>
                                                                    <option value="ID">ID - DME ASSOCIATED WITH ILS</option>
                                                                    <option value="IG">IG - GLIDE PATH (ILS)</option>
                                                                    <option value="II">II - INNER MARKER (ILS)</option>
                                                                    <option value="IL">IL - LOCALIZER (ILS)</option>
                                                                    <option value="IM">IM - MIDDLE MARKER (ILS)</option>
                                                                    <option value="IN">IN - LOCALIZER</option>
                                                                    <option value="IO">IO - OUTER MARKER (ILS)</option>
                                                                    <option value="IS">IS - ILS CATEGORY I</option>
                                                                    <option value="IT">IT - ILS CATEGORY II</option>
                                                                    <option value="IU">IU - ILS CATEGORY III</option>
                                                                    <option value="IW">IW - MLS</option>
                                                                    <option value="IX">IX - LOCATOR, OUTER, (ILS)</option>
                                                                    <option value="IY">IY - LOCATOR, MIDDLE (ILS)</option>
                                                                    <option value="LA">LA - APPROACH LIGHTING SYSTEM</option>
                                                                    <option value="LB">LB - AERODROME BEACON</option>
                                                                    <option value="LC">LC - RUNWAY CENTERLINE LIGHTS</option>
                                                                    <option value="LD">LD - LANDING DIRECTION INDICATOR LIGHTS</option>
                                                                    <option value="LE">LE - RUNWAY EDGE LIGHTS</option>
                                                                    <option value="LF">LF - SEQUENCED FLASHING LIGHTS</option>
                                                                    <option value="LG">LG - PILOT-CONTROLLED LIGHTING</option>
                                                                    <option value="LH">LH - HIGH INTENSITY RUNWAY LIGHTS</option>
                                                                    <option value="LI">LI - RUNWAY END IDENTIFIER LIGHTS</option>
                                                                    <option value="LJ">LJ - RUNWAY ALIGNMENT INDICATOR LIGHTS</option>
                                                                    <option value="LK">LK - CATEGORY II COMPONENTS OF APPROACH LIGHTING SYSTEM</option>
                                                                    <option value="LL">LL - LOW INTENSITY RUNWAY LIGHTS</option>
                                                                    <option value="LM">LM - MEDIUM INTENSITY RUNWAY LIGHTS</option>
                                                                    <option value="LP">LP - PRECISION APPROACH PATH INDICATOR</option>
                                                                    <option value="LR">LR - ALL LANDING AREA LIGHTING FACILITIES</option>
                                                                    <option value="LS">LS - STOPWAY LIGHTS</option>
                                                                    <option value="LT">LT - THRESHOLD LIGHTS</option>
                                                                    <option value="LU">LU - HELICOPTER APPROACH PATH INDICATOR</option>
                                                                    <option value="LV">LV - VISUAL APRROACH SLOPE INDICATOR</option>
                                                                    <option value="LW">LW - HELIPORT LIGHTING</option>
                                                                    <option value="LX">LX - TAXIWAY CENTER LINE LIGHTS</option>
                                                                    <option value="LY">LY - TAXIWAY EDGE LIGHTS</option>
                                                                    <option value="LZ">LZ - RUNWAY TOUCH DOWN ZONE LIGHTS</option>
                                                                    <option value="MA">MA - MOVEMENT AREA</option>
                                                                    <option value="MB">MB - BEARING STRENGTH</option>
                                                                    <option value="MC">MC - CLEARWAY</option>
                                                                    <option value="MD">MD - DECLARED DISTANCES</option>
                                                                    <option value="MG">MG - TAXIING GUIDANCE SYSTEM</option>
                                                                    <option value="MH">MH - RUNWAY ARRESTING GEAR</option>
                                                                    <option value="MK">MK - PARKING AREA</option>
                                                                    <option value="MM">MM - DAYLIGHT MARKINGS</option>
                                                                    <option value="MN">MN - APRON</option>
                                                                    <option value="MO">MO - STOP BAR</option>
                                                                    <option value="MP">MP - AIRCRAFT STANDS</option>
                                                                    <option value="MR">MR - RUNWAY</option>
                                                                    <option value="MS">MS - STOPWAY</option>
                                                                    <option value="MT">MT - THRESHOLD</option>
                                                                    <option value="MU">MU - RUNWAY TURNING BAY</option>
                                                                    <option value="MW">MW - STRIP</option>
                                                                    <option value="MX">MX - TAXIWAY</option>
                                                                    <option value="MY">MY - RAPID EXIT TAXIWAY</option>
                                                                    <option value="NA">NA - ALL RADIO NAVIGATION FACILITIES</option>
                                                                    <option value="NB">NB - NDB</option>
                                                                    <option value="NC">NC - DECCA</option>
                                                                    <option value="ND">ND - DME</option>
                                                                    <option value="NF">NF - FAN MARKER</option>
                                                                    <option value="NL">NL - LOCATOR</option>
                                                                    <option value="NM">NM - VOR/DME</option>
                                                                    <option value="NN">NN - TACAN</option>
                                                                    <option value="NT">NT - VORTAC</option>
                                                                    <option value="NV">NV - VOR</option>
                                                                    <option value="NX">NX - DIRECTION FINDING STATION</option>
                                                                    <option value="OA">OA - AERONAUTICAL INFORMATION SERVICE</option>
                                                                    <option value="OB">OB - OBSTACLE</option>
                                                                    <option value="OE">OE - AIRCRAFT ENTRY REQUIREMENTS</option>
                                                                    <option value="OL">OL - OBSTACLE LIGHTS</option>
                                                                    <option value="OR">OR - RESCUE COORDINATION CENTER</option>
                                                                    <option value="PA">PA - STANDARD INSTRUMENT ARRIVAL (STAR)</option>
                                                                    <option value="PB">PB - STANDARD VFR ARRIVAL</option>
                                                                    <option value="PC">PC - CONTINGENCY PROCEDURES</option>
                                                                    <option value="PD">PD - STANDARD INSTRUMENT DEPARTURE (SID)</option>
                                                                    <option value="PE">PE - STANDARD VFR DEPARTURE</option>
                                                                    <option value="PF">PF - FLOW CONTROL PROCEDURES</option>
                                                                    <option value="PH">PH - HOLDING PROCEDURES</option>
                                                                    <option value="PI">PI - INSTRUMENT APPROACH PROCEDURE</option>
                                                                    <option value="PK">PK - VFR APPROACH PROCEDURE</option>
                                                                    <option value="PL">PL - OBSTACLE CLEARANCE LIMIT</option>
                                                                    <option value="PM">PM - AERODROME OPERATING MINIMA</option>
                                                                    <option value="PN">PN - NOISE OPERATING RESTRICTIONS</option>
                                                                    <option value="PO">PO - OBSTACLE CLEARANCE ALTITUDE</option>
                                                                    <option value="PP">PP - OBSTACLE CLEARANCE HEIGHT</option>
                                                                    <option value="PR">PR - RADIO FAILURE PROCEDURE</option>
                                                                    <option value="PT">PT - TRANSITION ALTITUDE</option>
                                                                    <option value="PU">PU - MISSED APPROACH PROCEDURE</option>
                                                                    <option value="PX">PX - MINIMUM HOLDING ALTITUDE</option>
                                                                    <option value="PZ">PZ - ADIZ PROCEDURE</option>
                                                                    <option value="RA">RA - AIRSPACE RESERVATION</option>
                                                                    <option value="RD">RD - DANGER AREA</option>
                                                                    <option value="RM">RM -  </option>
                                                                    <option value="RO">RO - OVERFLYING OF</option>
                                                                    <option value="RP">RP - PROHIBITED AREA</option>
                                                                    <option value="RR">RR - RESTRICTED AREA</option>
                                                                    <option value="RT">RT - TEMPORARY RESTRICTED AREA</option>
                                                                    <option value="SA">SA - AUTOMATIC TERMINAL INFORMATION SERVICE (ATIS)</option>
                                                                    <option value="SB">SB - ATS REPORT OFFICE</option>
                                                                    <option value="SC">SC - AREA CONTROL CENTER</option>
                                                                    <option value="SE">SE - FLIGHT INFORMATION SERVICE</option>
                                                                    <option value="SF">SF - AERODROME FLIGHT INFORMATION SERVICE (AFIS)</option>
                                                                    <option value="SL">SL - FLOW CONTROL CENTER</option>
                                                                    <option value="SO">SO - OCEANIC AREA CONTROL CENTER</option>
                                                                    <option value="SP">SP - APPROACH CONTROL</option>
                                                                    <option value="SS">SS - FLIGHT SERVICE STATION</option>
                                                                    <option value="ST">ST - AERODROME CONTROL TOWER</option>
                                                                    <option value="SU">SU - UPPER AREA CONTROL CENTER</option>
                                                                    <option value="SV">SV - VOLMENT BROADCAST</option>
                                                                    <option value="SY">SY - UPPER ADVISORY SERVICE</option>
                                                                    <option value="TT">TT - MIJI</option>
                                                                    <option value="WA">WA - AIR DISPLAY</option>
                                                                    <option value="WB">WB - AEROBATICS</option>
                                                                    <option value="WC">WC - CAPTIVE BALLOON OR KITE</option>
                                                                    <option value="WD">WD - DEMOLITION OF EXPLOSIVES</option>
                                                                    <option value="WE">WE - EXERCISES</option>
                                                                    <option value="WF">WF - AIR REFUELING</option>
                                                                    <option value="WG">WG - GLIDER FLYING</option>
                                                                    <option value="WH">WH - BLASTING</option>
                                                                    <option value="WJ">WJ - BANNER/TARGET TOWING</option>
                                                                    <option value="WL">WL - ASCENT OF FREE BALLOON</option>
                                                                    <option value="WM">WM - MISSLE, GUN OR ROCKET FIRING</option>
                                                                    <option value="WP">WP - PARACHUTE JUMPING EXERCISE</option>
                                                                    <option value="WR">WR - RADIOACTIVE MATERIALS OR TOXIC CHEMICALS</option>
                                                                    <option value="WS">WS - BURNING OR BLOWING GAS</option>
                                                                    <option value="WT">WT - MASS MOVEMENT OF ACFT</option>
                                                                    <option value="WU">WU - UNMANNED AIRCRAFT</option>
                                                                    <option value="WV">WV - FORMATION FLT</option>
                                                                    <option value="WW">WW - SIGNIFICANT VOLCANIC ACTIVITY</option>
                                                                    <option value="WY">WY - AERIAL SURVEY</option>
                                                                    <option value="WZ">WZ - MODEL FLYING</option>
                                                                    <option value="XX">XX - OTHER</option>
                                                            </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="2" style="width: 45%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-code_list_2_3-container"><span class="select2-selection__rendered" id="select2-code_list_2_3-container" role="textbox" aria-readonly="true" title="AA - MINIMUM ALTITUDE">AA - MINIMUM ALTITUDE</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    <select id="code_list_4_5" name="code_list_4_5" style="width: 45%" data-select2-id="code_list_4_5" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                                                    <option value="AC" data-select2-id="5">AC - WITHDRAWN FOR MAINTENANCE</option>
                                                                    <option value="AD">AD - AVAILABLE FOR DAYLIGHT OPERATIONS</option>
                                                                    <option value="AF">AF - FLIGHT CHECKED AND FOUND RELIABLE</option>
                                                                    <option value="AG">AG - OPERATING BUT GROUND CHECKED ONLY, AWAITING FLIGHT CHECK</option>
                                                                    <option value="AH">AH - HOURS OF SERVICE ARE</option>
                                                                    <option value="AK">AK - RESUMED NORMAL OPERATIONS</option>
                                                                    <option value="AM">AM - MILITARY OPERATIONS ONLY</option>
                                                                    <option value="AN">AN - AVAILABLE FOR NIGHT OPERATIONS</option>
                                                                    <option value="AO">AO - OPERATIONAL</option>
                                                                    <option value="AP">AP - PRIOR PERMISSION REQUIRED</option>
                                                                    <option value="AQ">AQ - COMPLETELY WITHDRAWN</option>
                                                                    <option value="AR">AR - AVAILABLE, PRIOR PERMISSION REQUIRED</option>
                                                                    <option value="AS">AS - UNSERVICEABLE</option>
                                                                    <option value="AU">AU - NOT AVAILABLE</option>
                                                                    <option value="AW">AW - COMPLETELY WITHDRAWN</option>
                                                                    <option value="AX">AX - PREVIOUSLY PROMULGATED SHUTDOWN HAS BEEN CANCELLED</option>
                                                                    <option value="CA">CA - ACTIVATED</option>
                                                                    <option value="CC">CC - COMPLETED</option>
                                                                    <option value="CD">CD - DEACTIVATED</option>
                                                                    <option value="CE">CE - ERECTED</option>
                                                                    <option value="CF">CF - FREQUENCY CHANGED TO</option>
                                                                    <option value="CG">CG - DOWNGRADED TO</option>
                                                                    <option value="CH">CH - CHANGED</option>
                                                                    <option value="CI">CI - IDENTIFICATION OR RADIO CALL SIGN CHANGED TO</option>
                                                                    <option value="CL">CL - REALIGNED</option>
                                                                    <option value="CM">CM - DISPLACED</option>
                                                                    <option value="CO">CO - OPERATING</option>
                                                                    <option value="CP">CP - OPERATING ON REDUCED POWER</option>
                                                                    <option value="CR">CR - TEMPORARILY REPLACED BY</option>
                                                                    <option value="CS">CS - INSTALLED</option>
                                                                    <option value="CT">CT - ON TEST, DO NOT USE</option>
                                                                    <option value="GA">GA - NOT COINCIDENTAL WITH ILS/PAR</option>
                                                                    <option value="GB">GB - IN RAISED POSITION</option>
                                                                    <option value="GC">GC - TAIL HOOK ONLY</option>
                                                                    <option value="GD">GD - OFFICIAL BUSINESS ONLY</option>
                                                                    <option value="GE">GE - EXPECT LANDING DELAY</option>
                                                                    <option value="GF">GF - EXTENSIVE SERVICE DELAY</option>
                                                                    <option value="GG">GG - UNUSABLE BEYOND</option>
                                                                    <option value="GH">GH - UNUSABLE</option>
                                                                    <option value="GI">GI - UNMONITORED</option>
                                                                    <option value="GJ">GJ - IN PROGRESS</option>
                                                                    <option value="GK">GK - MODERATE</option>
                                                                    <option value="GL">GL - SEVERE</option>
                                                                    <option value="GM">GM - NOT ILLUMINATED</option>
                                                                    <option value="GN">GN - FREQUENCY NOT AVAILABLE</option>
                                                                    <option value="GO">GO - IS WET</option>
                                                                    <option value="GV">GV - NOT AUTHORIZED</option>
                                                                    <option value="HA">HA - BRAKING ACTION IS</option>
                                                                    <option value="HB">HB - BRAKING COEFFICIENT IS</option>
                                                                    <option value="HC">HC - COVERED BY COMPACTED SNOW TO A DEPTH OF</option>
                                                                    <option value="HD">HD - COVERED BY DRY SNOW TO A DEPTH OF</option>
                                                                    <option value="HE">HE - COVERED BY WATER TO A DEPTH OF</option>
                                                                    <option value="HF">HF - TOTALLY FREE OF SNOW AND ICE</option>
                                                                    <option value="HG">HG - GRASS CUTTING IN PROGRESS</option>
                                                                    <option value="HH">HH - HAZARD DUE TO</option>
                                                                    <option value="HI">HI - COVERED BY ICE</option>
                                                                    <option value="HJ">HJ - LAUNCH PLANNED</option>
                                                                    <option value="HK">HK - MIGRATION IN PROGRESS</option>
                                                                    <option value="HL">HL - SNOW CLEARANCE COMPLETED</option>
                                                                    <option value="HM">HM - MARKED BY</option>
                                                                    <option value="HN">HN - COVERED BY WET SNOW OR SLUSH TO A DEPTH OF</option>
                                                                    <option value="HO">HO - OBSCURED BY SNOW</option>
                                                                    <option value="HP">HP - SNOW CLEARANCE IN PROGRESS</option>
                                                                    <option value="HQ">HQ - OPERATIONS CANCELLED</option>
                                                                    <option value="HR">HR - STANDING WATER</option>
                                                                    <option value="HS">HS - SANDING</option>
                                                                    <option value="HT">HT - APPROACH ACCORDING TO SIGNAL AREA ONLY</option>
                                                                    <option value="HU">HU - LAUNCH IN PROGRESS</option>
                                                                    <option value="HV">HV - WORK COMPLETED</option>
                                                                    <option value="HW">HW - WORK IN PROGRESS</option>
                                                                    <option value="HX">HX - CONCENTRATION OF BIRDS</option>
                                                                    <option value="HY">HY - SNOW BANKS EXIST</option>
                                                                    <option value="HZ">HZ - COVERED BY FROZEN RUTS AND RIDGES</option>
                                                                    <option value="KK">KK - Volcanic Activity</option>
                                                                    <option value="LA">LA - OPERATING ON AUXILIARY POWER SUPPLY</option>
                                                                    <option value="LB">LB - RESERVED FOR AIRCRAFT BASED THEREIN</option>
                                                                    <option value="LC">LC - CLOSED</option>
                                                                    <option value="LD">LD - UNSAFE</option>
                                                                    <option value="LE">LE - OPERATING WITHOUT AUXILIARY POWER SUPPLY</option>
                                                                    <option value="LF">LF - INTERFERENCE FROM</option>
                                                                    <option value="LG">LG - OPERATING WITHOUT IDENTIFICATION</option>
                                                                    <option value="LH">LH - UNSERVICEABLE FOR AIRCRAFT HEAVIER THAN</option>
                                                                    <option value="LI">LI - CLOSED TO IFR OPERATIONS</option>
                                                                    <option value="LK">LK - OPERATING AS A FIXED LIGHT</option>
                                                                    <option value="LL">LL - USABLE FOR LENGTH OF AND WIDTH OF</option>
                                                                    <option value="LN">LN - CLOSED TO ALL NIGHT OPERATIONS</option>
                                                                    <option value="LP">LP - PROHIBITED TO</option>
                                                                    <option value="LR">LR - AIRCRAFT RESTRICTED TO RUNWAYS AND TAXIWAYS</option>
                                                                    <option value="LS">LS - SUBJECT TO INTERRUPTION</option>
                                                                    <option value="LT">LT - LIMITED TO</option>
                                                                    <option value="LV">LV - CLOSED TO VFR OPERATIONS</option>
                                                                    <option value="LW">LW - WILL TAKE PLACE</option>
                                                                    <option value="LX">LX - OPERATING BUT CAUTION ADVISED DUE TO</option>
                                                                    <option value="LY">LY - EFFECTIVE</option>
                                                                    <option value="TT">TT - HAZARD</option>
                                                                    <option value="XX">XX - OTHER</option>
                                                            </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="4" style="width: 45%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-code_list_4_5-container"><span class="select2-selection__rendered" id="select2-code_list_4_5-container" role="textbox" aria-readonly="true" title="AC - WITHDRAWN FOR MAINTENANCE">AC - WITHDRAWN FOR MAINTENANCE</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><button id="add_code_list">Add</button>
                                </div>
                                <label for="code_list">Aerodrome List</label>
                                <div class="form-group">
                                    <select class="form-control select2-hidden-accessible" id="aerodrome_list" name="aerodrome_list[]" multiple="" style="width:100%" data-select2-id="aerodrome_list" tabindex="-1" aria-hidden="true"></select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="7" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    <select id="aerodrome" name="aerodrome" style="width: 92%" data-select2-id="aerodrome" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                                                    <option value="WITN" data-select2-id="9">WITN - Maimun Saleh - Sabang</option>
                                                                    <option value="WITT">WITT - Sultan Iskandar Muda - Banda Aceh</option>
                                                                    <option value="WITC">WITC - Cut Nyak Dien Nagan Raya - Meulaboh, NAD</option>
                                                                    <option value="WIMA">WIMA - Malikussaleh - Lhokseumawe</option>
                                                                    <option value="WIMB">WIMB - Binaka - Gunung Sitoli</option>
                                                                    <option value="WIMM">WIMM - Kualanamu International Airport - Deli Serdang</option>
                                                                    <option value="WIMS">WIMS - Dr. Ferdinand Lumban Tobing - Pinangsori (Sibolga)</option>
                                                                    <option value="WIME">WIME - Aek Godang - Padang Sidempuan</option>
                                                                    <option value="WIEE">WIEE - Minangkabau International Airport - Padang</option>
                                                                    <option value="WIBB">WIBB - Sultan Syarif Kasim II - Pekanbaru</option>
                                                                    <option value="WIBJ">WIBJ - Japura - Rengat</option>
                                                                    <option value="WIGG">WIGG - Fatmawati Sukarno - Bengkulu</option>
                                                                    <option value="WIDT">WIDT - Seibati - Tanjung Balai Karimun</option>
                                                                    <option value="WIJJ">WIJJ - Sultan Thaha - Jambi</option>
                                                                    <option value="WIDD">WIDD - Hang Nadim - Batam</option>
                                                                    <option value="WIDN">WIDN - Raja Haji Fisabilillah - Tanjung Pinang</option>
                                                                    <option value="WIDS">WIDS - Dabo - Singkep</option>
                                                                    <option value="WIPP">WIPP - Sultan Mahmud Badaruddin II - Palembang</option>
                                                                    <option value="WILL">WILL - Radin Inten II - Lampung</option>
                                                                    <option value="WIRR">WIRR - Budiarto - Curug</option>
                                                                    <option value="WIII">WIII - Soekarno Hatta - Jakarta</option>
                                                                    <option value="WIHJ">WIHJ - Atang Sendjaja - Bogor</option>
                                                                    <option value="WIHH">WIHH - Halim Perdanakusuma - Jakarta</option>
                                                                    <option value="WICC">WICC - Husein Sastranegara - Bandung</option>
                                                                    <option value="WIKT">WIKT - H.A.S. Hanandjoeddin - Tanjung Pandan</option>
                                                                    <option value="WICA">WICA - Kertajati</option>
                                                                    <option value="WICD">WICD - Cakrabhuwana / Penggung - Cirebon</option>
                                                                    <option value="WIOO">WIOO - Supadio - Pontianak</option>
                                                                    <option value="WIOK">WIOK - Rahadi Osman - Ketapang</option>
                                                                    <option value="WAHS">WAHS - Ahmad Yani - Semarang</option>
                                                                    <option value="WAHQ">WAHQ - Adi Soemarmo - Surakarta</option>
                                                                    <option value="WIOS">WIOS - Susilo - Sintang</option>
                                                                    <option value="WIOG">WIOG - Nanga Pinoh</option>
                                                                    <option value="WARA">WARA - Abdulrachman Saleh - Malang</option>
                                                                    <option value="WARR">WARR - Juanda - Surabaya</option>
                                                                    <option value="WAGS">WAGS - Haji Asan - Sampit</option>
                                                                    <option value="WAGG">WAGG - Tjilik Riwut - Palangkaraya</option>
                                                                    <option value="WAGK">WAGK - Beringin - Muara Teweh</option>
                                                                    <option value="WAGM">WAGM - Sanggu - Buntok</option>
                                                                    <option value="WATE">WATE - H. Hasan Aroeboesman</option>
                                                                    <option value="WAOO">WAOO - Syamsudin Noor - Banjarmasin</option>
                                                                    <option value="WADD">WADD - Ngurah Rai - Denpasar</option>
                                                                    <option value="WADL">WADL - Bandara Internasional Lombok - Praya</option>
                                                                    <option value="WAOK">WAOK - Gusti Sjamsir Alam - Kota Baru</option>
                                                                    <option value="WALL">WALL - Sepinggan - Balikpapan</option>
                                                                    <option value="WALS">WALS - Temindung - Samarinda</option>
                                                                    <option value="WAQD">WAQD - Tanjung Harapan - Tanjung Selor</option>
                                                                    <option value="WADS">WADS - Sultan Muhammad Kaharuddin - Sumbawa Besar</option>
                                                                    <option value="WAQT">WAQT - Kalimarau - Tanjung Redeb</option>
                                                                    <option value="WAQQ">WAQQ - Juwata - Tarakan</option>
                                                                    <option value="WAQA">WAQA - Nunukan</option>
                                                                    <option value="WAAA">WAAA - Sultan Hasanuddin - Makassar</option>
                                                                    <option value="WAFT">WAFT - Pongtiku - Tana Toraja</option>
                                                                    <option value="WATO">WATO - Komodo - Labuan Bajo</option>
                                                                    <option value="WAFF">WAFF - Mutiara - Palu</option>
                                                                    <option value="WATU">WATU - Umbu Mehang Kunda - Waingapu</option>
                                                                    <option value="WAFM">WAFM - Andi Jemma - Masamba</option>
                                                                    <option value="WATG">WATG - Frans Sales Lega - Ruteng</option>
                                                                    <option value="WAFP">WAFP - Kasiguncu - Poso</option>
                                                                    <option value="WAFL">WAFL - Sultan Bantilan - Toli Toli</option>
                                                                    <option value="WATS">WATS - Tardamu - Sabu</option>
                                                                    <option value="WATC">WATC - Fransiskus Xaverius Seda - Maumere</option>
                                                                    <option value="WAWB">WAWB - Beto Ambari - Bau Bau</option>
                                                                    <option value="WAFW">WAFW - Syukuran Aminuddin Amir - Luwuk</option>
                                                                    <option value="WAMG">WAMG - Djalaluddin - Gorontalo</option>
                                                                    <option value="WATL">WATL - Gewayantana - Larantuka</option>
                                                                    <option value="WATR">WATR - David Constantijn Saudale / Lekunik - Rote</option>
                                                                    <option value="WATT">WATT - El Tari - Kupang</option>
                                                                    <option value="WATM">WATM - Mali Kalabahi - Alor</option>
                                                                    <option value="WAMM">WAMM - Sam Ratulangi - Manado</option>
                                                                    <option value="WAEE">WAEE - Sultan Babullah - Ternate</option>
                                                                    <option value="WAEL">WAEL - Oesman Sadik - Labuha</option>
                                                                    <option value="WAEG">WAEG - Gamar Malamo - Galela</option>
                                                                    <option value="WAPP">WAPP - Pattimura - Ambon</option>
                                                                    <option value="WAEW">WAEW - Leo Watimena - Morotai</option>
                                                                    <option value="WAPA">WAPA - Amahai</option>
                                                                    <option value="WAPC">WAPC - Bandaneira - Banda</option>
                                                                    <option value="WASS">WASS - Seigun - Sorong Kota</option>
                                                                    <option value="WASF">WASF - Torea - Fak Fak</option>
                                                                    <option value="WAPL">WAPL - Dumatubun - Langgur</option>
                                                                    <option value="WASK">WASK - Utarom - Kaimana</option>
                                                                    <option value="WAUU">WAUU - Rendani - Manokwari</option>
                                                                    <option value="WABI">WABI - Nabire</option>
                                                                    <option value="WABB">WABB - Frans Kaisiepo - Biak</option>
                                                                    <option value="WAYY">WAYY - Moses Kilangin - Timika</option>
                                                                    <option value="WAJJ">WAJJ - Sentani - Jayapura</option>
                                                                    <option value="WAHL">WAHL - Tunggul Wulung</option>
                                                                    <option value="WADY">WADY - Blimbingsari - Banyuwangi</option>
                                                                    <option value="WADB">WADB - Sultan Muhammad Salahuddin - Bima</option>
                                                                    <option value="WAYE">WAYE - Enarotali</option>
                                                                    <option value="WIMN">WIMN - Silangit - Tapanuli Utara</option>
                                                                    <option value="WIJI">WIJI - Depati Parbo - Kerinci</option>
                                                                    <option value="WIKK">WIKK - Depati Amir - Pangkal Pinang</option>
                                                                    <option value="WAHH">WAHH - Adisutjipto - Yogyakarta</option>
                                                                    <option value="WIOP">WIOP - Pangsuma - Putussibau</option>
                                                                    <option value="WAQJ">WAQJ - Yuvai Semaring - Long Bawang</option>
                                                                    <option value="WAFJ">WAFJ - Mamuju</option>
                                                                    <option value="WATK">WATK - Tambolaka - Sumba Barat</option>
                                                                    <option value="WAWP">WAWP - Pomala - Kolaka</option>
                                                                    <option value="WAWW">WAWW - Wolter Monginsidi - Kendari</option>
                                                                    <option value="WATA">WATA - A.A. Bere Talo</option>
                                                                    <option value="WAMH">WAMH - Naha - Tahuna</option>
                                                                    <option value="WAES">WAES - Emalamo - Sanana</option>
                                                                    <option value="WAPR">WAPR - Namlea</option>
                                                                    <option value="WAEK">WAEK - Kuabang Kao</option>
                                                                    <option value="WAPS">WAPS - Mathilda Batlayeri - Saumlaki</option>
                                                                    <option value="WAPF">WAPF - Karel Sadsuitubun - Tual</option>
                                                                    <option value="WABO">WABO - Sudjarwo Tjondronegoro - Serui</option>
                                                                    <option value="WAJI">WAJI - Mararena / Orai - Sarmi</option>
                                                                    <option value="WAVV">WAVV - Wamena</option>
                                                                    <option value="WAKT">WAKT - Tanah Merah</option>
                                                                    <option value="WAGI">WAGI - Iskandar - Pangkalan Bun</option>
                                                                    <option value="WILM">WILM - Astra Ksetra - Lampung</option>
                                                                    <option value="WICS">WICS - Suryadarma - Kalijati, Subang</option>
                                                                    <option value="WICM">WICM - Wiriadinata - Cibeureum, Tasikmalaya</option>
                                                                    <option value="WARI">WARI - Iswahjudi - Madiun</option>
                                                                    <option value="WARE">WARE - Noto Hadinegoro - Jember</option>
                                                                    <option value="WAKK">WAKK - Mopah - Merauke</option>
                                                                    <option value="WART">WART - Trunojoyo - Sumenep</option>
                                                                    <option value="WIGM">WIGM - Mukomuko</option>
                                                                    <option value="WAHI">WAHI - Yogyakarta International Airport - Kulonprogo</option>
                                                                    <option value="WATB">WATB - SOA - Bajawa</option>
                                                                    <option value="WATW">WATW - Wunopito - Lembata</option>
                                                                    <option value="WIDO">WIDO - Ranai - Natuna</option>
                                                                    <option value="WARW">WARW - Bawean - Harun Thohir </option>
                                                                    <option value="WIET">WIET - LANUD Sutan Sjahrir</option>
                                                            </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="8" style="width: 92%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-aerodrome-container"><span class="select2-selection__rendered" id="select2-aerodrome-container" role="textbox" aria-readonly="true" title="WITN - Maimun Saleh - Sabang">WITN - Maimun Saleh - Sabang</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><button id="add_aerodrome_list">Add</button>
                                </div>
                                <label for="code_list">NOTAM Text</label>
                                <div class="form-group">
                                    <div class="radio radio-primary">
                                        <input type="radio" id="keyword_options_1" name="keyword_options" value="included" checked="">
                                        <label for="keyword_options_1">To Be Included</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" id="keyword_options_2" name="keyword_options" value="except">
                                        <label for="keyword_options_2">All Except</label>
                                    </div>
                                    <div class="radio radio-primary">
                                    </div>
                                    <select class="form-control select2-hidden-accessible" id="keyword_list" name="keyword_list[]" multiple="" style="width: 100%" data-select2-id="keyword_list" tabindex="-1" aria-hidden="true"></select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="6" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    <input type="text" id="keyword" placeholder="KEYWORD" style="width: 92%"><button id="add_keyword_list">Add</button>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Q-Code</label>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="q_code_options_1" name="q_code_options" value="only_included" checked="">
                                        <label for="q_code_options_1">Only to be included</label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="q_code_options_2" name="q_code_options" value="all_minus">
                                        <label for="q_code_options_2">All Minus</label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="q_code_options_3" name="q_code_options" value="all_plus">
                                        <label for="q_code_options_3">All Plus</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>NOTAM Validity</label>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="notam_validity_options_1" name="notam_validity_options" value="notam_validity_all" checked="">
                                        <label for="notam_validity_options_1">All</label>
                                    </div>
                                    <div class="radio radio-primary">
                                        <input type="radio" id="notam_validity_options_2" name="notam_validity_options" value="notam_validity_not_all">
                                        <label for="notam_validity_options_2">Not older than: <input type="text" name="notam_validity_days"> days</label>
                                    </div>
                                </div>
                                <label>Flight Rules</label>
                                <div class="form-group">
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="I" id="flight_rules_1" name="flight_rules[]" checked="">
                                        <label for="flight_rules_1">IFR</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="V" id="flight_rules_2" name="flight_rules[]">
                                        <label for="flight_rules_2">VFR</label>
                                    </div>
                                </div>
                                <label>Scope</label>
                                <div class="form-group">
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="A" id="scope_1" name="scope[]" checked="">
                                        <label for="scope_1">A</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="E" id="scope_2" name="scope[]">
                                        <label for="scope_2">E</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="W" id="scope_3" name="scope[]">
                                        <label for="scope_3">W</label>
                                    </div>
                                </div>
                                <label>Purpose</label>
                                <div class="form-group">
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="N" id="purpose_1" name="purpose[]">
                                        <label for="purpose_1">N</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="B" id="purpose_2" name="purpose[]" checked="">
                                        <label for="purpose_2">B</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="O" id="purpose_3" name="purpose[]">
                                        <label for="purpose_3">O</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input type="checkbox" value="M" id="purpose_4" name="purpose[]">
                                        <label for="purpose_4">M</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                            <input type="submit" value="Submit" name="submit">
                        </form>
                </div> -->
                <div class="row mt-3">
                    <div class="col-md-12" id="airport" style="visibility:visible">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark" align="center">
                                <tr>
                                    <th style="cursor:pointer">Name</th>
                                    <th style="cursor:pointer">ICAO</th>
                                    <th style="cursor:pointer">City</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($airport as $arpt)
                                    <tr class="nk-tb-item" v-bind:key="airport.arpt_ident" collapse="0" id="{{ $arpt->arpt_ident }}">
                                        <td style="cursor:pointer" id={{ $arpt->arpt_ident.'_1' }} class="arptlstpdf"><a onclick="GetNotam('{{ $arpt->icao }}')">{{ $arpt->arpt_name }}</a></td>
                                        <td style="cursor:pointer" id={{ $arpt->arpt_ident.'_2' }} class="arptlstpdf"><a onclick="GetNotam('{{ $arpt->icao }}')">{{ $arpt->icao }}</a></td>
                                        <td style="cursor:pointer" id={{ $arpt->arpt_ident.'_3' }} class="arptlstpdf"><a onclick="GetNotam('{{ $arpt->icao }}')">{{ $arpt->city_name }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12" id="airspace" style="visibility:hidden">
                        <table class="datatable-init table table-bordered table-hover" id="table-content">
                            <thead class="thead-dark" align="center">
                                <tr>
                                    <th style="cursor:pointer">Name</th>
                                    <th style="cursor:pointer">ICAO</th>
                                    <th style="cursor:pointer">Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($airspace as $arpt)
                                    <tr class="nk-tb-item" v-bind:key="airport.arpt_ident" collapse="0" id="{{ $arpt->arpt_ident }}">
                                        <td style="cursor:pointer" id={{ $arpt->ats_airspace_id.'_1' }} class="arptlstpdf"><a onclick="GetNotam('{{ $arpt->icao_acc }}')">{{ $arpt->airspace_name }} {{ $arpt->airspace_type }}</a></td>
                                        <td style="cursor:pointer" id={{ $arpt->ats_airspace_id.'_2' }} class="arptlstpdf"><a onclick="GetNotam('{{ $arpt->icao_acc }}')">{{ $arpt->icao_acc }}</a></td>
                                        <td style="cursor:pointer" id={{ $arpt->ats_airspace_id.'_3' }} class="arptlstpdf"><a onclick="GetNotam('{{ $arpt->icao_acc }}')">{{ $arpt->ats_unit }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('footer_scripts') 
<script type="text/javascript"> 
$("#aixmtitle").hide();
$("#airspace").hide();
function selectradio(){
    aboutvol("airport");
    aboutvol("airspace");
}
var  isarpt= false,
            isnavaid= false,
            isprocedure= false,
            isairspace= false,
            iswaypoint= false,
            iscomm= false,
            isairways= false,
            isholding= false,
            isobstacle= false,
            isall= false,
            isfinish= false;
function showmodal(){
    aboutvol("aixmtitle");
}
function remove(iframe){
    this.iframeLoaded = false;
    var frame = document.getElementById(iframe);
    if (frame !== null){
        frame.src = ''; 
        // try{ 
        //     frame.contentWindow.document.write(''); 
        //     frame.contentWindow.document.clear(); 
        // }catch(e){
        //     console.log('err')
        // } 
        this.iframeLoaded = true;
        frame.parentNode.removeChild(frame);
    }
}
// function GetNotam(value,isasp){
//     this.iframeLoaded = false;
//     var icao=''
//     this.pdfLink=null;
//     this.filename=""
//     this.urlpdf = '/datasource/INDONESIA/',
//     remove("iframepdf")
//     // console.log('this.pdfLink',value,this.pdfLink)
//                 this.iframeLoaded = true;
//                             var collapse,div;
//                         if (isasp==true){
//                             icao=value.icao_acc
//                             collapse = document.getElementById(value).getAttribute('collapse');
//                             div =document.getElementById(value)
//                         }else{
//                             icao=value.icao
//                             collapse = document.getElementById(value.arpt_ident).getAttribute('collapse');
//                             div =document.getElementById(value.arpt_ident)
//                         }
//                         this.pdfLink = 'http://aim-jakarta.co.id/searchpib/?link=view2&aero1=' +icao;
//                         remove("trpdf")
//                         remove("tdpdf")
//                         // console.log(collapse,div)
//                             if(collapse =='0'){
//                                 var newElement = document.createElement('tr');
//                                 newElement.setAttribute('id', 'trpdf');
//                                 // newElement.setAttribute('collapse', '1');
//                                 // newElement.insertCell(0).innerHTML = '<td colSpan="6"><iframe style="border: 0; padding: 0; width: 100%; height: 650px" src="'+ this.pdfLink +'"></iframe></td>'
//                                 div.parentNode.insertBefore(newElement, div.nextSibling );
//                                 var newtd = document.createElement('td');
//                                 newtd.setAttribute('colSpan', '6');
//                                 newtd.setAttribute('id', 'tdpdf');
//                                 newtd.innerHTML = '<iframe style="border: 0; padding: 0; width: 100%; height: 650px" src="'+ this.pdfLink +'"></iframe>'
//                                 div.parentNode.insertBefore(newtd, newElement.nextSibling );
//                                 div.setAttribute('collapse', '1');
//                                 // document.getElementsByName("opentr").setAttribute('collapse', '1');
//                                 // // document.getElementsByClassName("opentr").setAttribute('collapse', '1');
//                                 // document.getElementById('td' + menu).colSpan="6"
//                                 //    var row = document.createElement('<tr class="opentr"><td colspan="5"><iframe style="border: 0; padding: 0; width: 100%; height: 800px" src="'+ this.pdfLink +'"></iframe></td></tr>').insertRow(1);
//                                 //    console.log(row)
//                                     // newElement(menu).setAttribute('collapse', '1');
//                                     // div.appendChild(row)
                                    
//                             }else{
//                                 remove("trpdf")
//                                 remove("tdpdf")
//                                 div.setAttribute('collapse', '0');
//                                     // div('.opentr').remove();
//                                     // div(menu).setAttribute('collapse', '0');
//                             }
//     //http://aim-jakarta.co.id/searchpib/?link=view2&aero1=WIIF
//     // let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
//     // window.open('http://aim-jakarta.co.id/searchpib/?link=view2&aero1=' + value.icao , 'GPS RAIM PREDICTool', params)
// }
function GetNotam(icao){
    let pdfLink = 'http://aim-jakarta.co.id/searchpib/?link=view2&aero1=' +icao;
    let params = `status=no,location=no,toolbar=no,menubar=no,hight=600,left=400,top=100`;
    window.open(pdfLink , 'AIM JAKARTA', params);
}
</script>
@endsection
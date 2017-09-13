<div id="parts-modal" class="modal-block" role="dialog">
	<div class="modal-inner">
		<div class="modal-inner-content">
			<div class="modal-header">
				<button type="button" class="close-button" data-dismiss="modal" aria-label="Close" onclick="close_modal('parts-modal');"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Find Your Auto Parts</h3>
			</div><!--End modal-header-->
			<div class="modal-header">
				<div class="row">
					<div class="col-xs-6">
						<h4>Your selected vehicle:</h4>
						<p>Make: <span id="modal-maker" class="bold" ><?php echo $maker; ?></span></p>
						<p>Year: <span id="modal-year" class="bold"><?php echo $year; ?></span> </p>
						<p>Model: <span id="modal-model" class="bold"><?php echo $model; ?></span></p>
						<p>Engine: <span id="modal-engine" class="bold"><?php echo $engine; ?></span></p>
						<p>Trim: <span id="modal-trim" class="bold"><?php echo $trim; ?></span></p>
					</div>
					<div class="col-xs-6">
						<img src="<?php echo $image_title; ?>"></img>
					</div>
				</div><!--End Row-->	
			</div><!--End Modal-header-->
			<div class="modal-body">

 <ul class="nav nav-tabs" role="tablist">
    <li id="tab-step1" role="presentation" class="active"><a href="#step1" aria-controls="home" role="tab" data-toggle="tab">Step 1</a></li>
    <li id="tab-step2" role="presentation" class="mouse-disable"><a href="#step2" aria-controls="profile" role="tab" data-toggle="tab">Step 2</a></li>
</ul>

  <!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="step1">
  	<div class="parts-select-block-main">
  		<h3>Choose your group:</h3>
  		  <select id="part-group-select">
  		    <option value="accessories">Accessories</option>  
  		    <option value="belt-drive">Belt Drive</option>
  		    <option value="body">Body</option>
  		    <option value="brake-wheelhub">Brake &amp; Wheel Hub</option>
  		    <option value="cooling-system">Cooling System</option>
  		    <option value="drivetrain">Drivetrain</option>
  		    <option value="electrical">Electrical</option>
  		    <option value="electrical-bulb-socket">Electrical-Bulb &amp; Socket</option>
  		    <option value="electrical-connector">Electrical-Connector</option>
  		    <option value="parts-select-block">Electrical-Switch &amp; Relay</option>
  		    <option value="engine">Engine</option>
  		    <option value="exaust">Exhaust &amp; Emission</option>
  		    <option value="fuel-air">Fuel &amp; Air</option>
  		    <option value="heat-airconditioning">Heat &amp; Air Conditioning</option>
  		    <option value="ignition">Ignition</option>
  		    <option value="interior">Interior</option>
  		    <option value="steering">Steering</option>
  		    <option value="suspension">Suspension</option>
  		    <option value="transmission-automatic">Transmission-Automatic</option>
  		    <option value="transmission-manual">Transmission-Manual</option>
  		    <option value="wheel">Wheel</option>
  		    <option value="wiper">Wiper &amp; Washer</option>
  		  </select>
	  </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="step2">
    <h3>Select Part: </h3>
    <div id="accessories" class="parts-select-block">
    <h4>Accessories</h4>
    <select>
        <option>Floor Liner</option>
        <option>Hood Deflector</option>
        <option>Hood Scoop</option>
        <option>Side Window Vent</option>
        <option>Trailer Hitch</option>
    </select>
    </div>
    <div id="belt-drive" class="parts-select-block">
      <h4>Belt Drive</h4>
      <select>
        <option>Belt</option>
        <option>Belt Drive Component Kit</option>
        <option>Belt Tensioner</option>  
        <option>Tensioner Pulley</option>
    </select>
    </div>
    
    <div id="body" class="parts-select-block">
      <h4>Body</h4>
      <select>
        <option>Air Deflector</option>
        <option>Antenna</option>
        <option>Antenna Base</option>  
        <option>Antenna Cable Extension</option>
        <option>Body Side Molding</option>
        <option>Bumper Cover</option>
        <option>Bumper Cover Support</option>
        <option>Bumper Energy Absorber</option>
        <option>Bumper Insert</option>
        <option>Bumper Reinforcement</option>
        <option>Bumper Spoiler</option>
        <option>Door</option>
        <option>Door Lock Actuator</option>
        <option>Fender</option>
        <option>Fender Brace</option>
        <option>Fog / Driving Lamp Assembly</option>
        <option>Fog Lamp Bezel</option>
        <option>Grille</option>
        <option>Grille Mounting Bracket</option>
        <option>Header Panel</option>
        <option>Headlamp Assembly</option>
        <option>Headlamp Bracket</option>
        <option>Hood</option>
        <option>Hood Hinge</option>
        <option>Impact Sensor</option>
        <option>Inner Fender</option>
        <option>License Plate Bracket</option>
        <option>Outside Door Handle</option>
        <option>Outside Mirror</option>
        <option>Outside Mirror Glass</option>
        <option>Radiator Support</option>
        <option>Rear Panel</option>
        <option>Side Marker Lamp Assembly</option>
        <option>Tail Lamp Assembly</option>
        <option>Trunk / Deck Lid</option>
        <option>Under Car Shield</option>
        <option>other</option>
    </select>
    </div>
    
    <div id="brake-wheelhub" class="parts-select-block">
      <h4>Brake  &amp; Wheel Hub</h4>
      <select>
        <option>ABS Control Module</option>
        <option>ABS Modulator Valve</option>
        <option>ABS Wheel Speed Sensor</option>
        <option>Brake Backing Plate</option>
        <option>Brake Bleeder</option>
        <option>Brake Bleeder Tool</option>
        <option>Brake Fluid Level Sensor</option>
        <option>Brake Pad</option>
        <option>Brake Pad Shim Kit</option>
        <option>Brake Pedal Position Sensor</option>
        <option>Brake Shoe</option>
        <option>Brake Spring Hold Down Pin</option>
        <option>Caliper</option>
        <option>Caliper Bracket</option>
        <option>Caliper Bracket Mounting Bolt</option>
        <option>Caliper Guide Pin</option>
        <option>Caliper Guide Pin Boot Kit</option>
        <option>Caliper Piston</option>
        <option>Caliper Piston Seal</option>
        <option>Caliper Repair Kit</option>
        <option>Disc Brake Hardware Kit</option>
        <option>Drum</option>
        <option>Drum / Shoe / Wheel Cylinder Kit</option>
        <option>Drum Brake Hardware Kit</option>
        <option>Drum Brake Self Adjuster Repair Kit</option>
        <option>Hold Down Spring Cup</option>
        <option>Hydraulic Hose</option>
        <option>Hydraulic Hose to Caliper (Banjo) Bolt Washer</option>
        <option>Hydraulic Line</option>
        <option>Master Cylinder</option>
        <option>Master Cylinder Reservoir</option>
        <option>Parking Brake Cable</option>
        <option>Power Brake Booster</option>
        <option>Power Brake Booster Check Valve</option>
        <option>Power Brake Booster Gasket</option>
        <option>Power Brake Booster Sensor</option>
        <option>Return Spring</option>
        <option>Rotor</option>
        <option>Rotor &amp; Brake Pad Kit</option>
        <option>Vacuum Hose</option>
        <option>Vacuum Pump</option>
        <option>Wheel Bearing &amp; Hub Assembly</option>
        <option>Wheel Cylinder</option>
        <option>Wheel Cylinder Repair Kit</option>
        <option>Wheel Hub</option>
    </select>
    </div>
    
      
    <div id="cooling-system" class="parts-select-block">
      <h4>Cooling System</h4>
        <select>
          <option>Coolant Air Bleeder</option>
          <option>Coolant Recovery Tank</option>
          <option>Coolant Recovery Tank Cap</option>
          <option>Coolant Recovery Tank Hose</option>
          <option>Cooling Fan Controller</option>
          <option>Cooling System Pressure Testing Kit</option>
          <option>Cooling System Tester Adapter</option>
          <option>Radiator</option>
          <option>Radiator Drain Petcock</option>
          <option>Radiator Fan Assembly</option>
          <option>Radiator Lower Hose</option>
          <option>Radiator Mount Bushing</option>
          <option>Radiator Upper Hose</option>
          <option>Temperature Sender / Sensor</option>
          <option>Thermostat</option>
          <option>Thermostat / Thermostat Housing / Water Outlet Seal</option>
          <option>Thermostat Housing / Water Outlet</option>
          <option>Water Pump</option>
          <option>Water Pump Gasket</option>
          <option>Water Pump Pulley</option>
      </select>
    </div>
    
    <div id="drivetrain" class="parts-select-block">
      <h4>Drivetrain</h4>
      <select>
        <option>Axle Shaft Seal</option>
        <option>CV Half Shaft Assembly</option>
        <option>Differential Bearing</option>
      </select>
    </div> 
    <div id="electrical" class="parts-select-block">
      <h4>Electrical</h4>
      <select>
        <option>Alternator / Generator</option>
        <option>Anti-Theft Control Module</option>
        <option>Automatic Headlamp Sensor</option>
        <option>Battery Cable</option>
        <option>Battery Cable Harness</option>
        <option>Battery Current Sensor</option>
        <option>Battery Hold Down</option>
        <option>Body Control Module (BCM)</option>
        <option>Engine Control Module (ECM Computer)</option>
        <option>Fuse Block</option>
        <option>Parking Aid Sensor</option>
        <option>Speed Sensor</option>
        <option>Speed Sensor Reluctor Ring</option>
        <option>Starter Motor</option>
        <option>Yaw Sensor</option>
      </select>
    </div> 
    
    <div id="electrical-bulb-socket" class="parts-select-block">
      <h4>Electrical-Bulb  Socket</h4>
      <select>
        <option>Back Up / Reverse Lamp Bulb</option>  
        <option>Brake Light Bulb</option>
        <option>Daytime Running Light Bulb</option>
        <option>Dome Light Bulb</option>
        <option>Fog / Driving Light Bulb</option>
        <option>Glove Box Light Bulb</option>
        <option>Headlamp Bulb</option>
        <option>Headlamp Socket</option>
        <option>License Plate Lamp Bulb</option>
        <option>Map / Reading Light Bulb</option>
        <option>Parking Lamp Bulb</option>
        <option>Side Marker Light Bulb</option>
        <option>Tail Lamp Bulb</option>
        <option>Trunk or Cargo Area Light Bulb</option>
        <option>Turn Signal Lamp Bulb</option>
        <option>Turn Signal Lamp Socket</option>
        <option>Vanity Mirror Light Bulb</option>
      </select>
    </div>
    
    <div id="electrical-connector" class="parts-select-block">
      <h4>Electrical-Connector</h4>
      <select>
        <option>A/C Refrigerant Pressure Sensor Connector</option>
        <option>ABS Wheel Speed Sensor Connector</option>
        <option>Ambient Air Temperature Sensor Connector</option>
        <option>Back Up Lamp Switch Connector</option>
        <option>Brake Fluid Level Sensor Connector</option>
        <option>Camshaft Position Sensor Connector</option>
        <option>Chassis Connector</option>
        <option>Connector</option>
        <option>Crankshaft Position Sensor Connector</option>
        <option>Fuel Injector Connector</option>
        <option>Fuel Tank Pressure Sensor Connector</option>
        <option>Ignition Coil Connector</option>
        <option>Inline Connector</option>
        <option>Manifold Pressure (MAP) Sensor Connector</option>
        <option>Oil Pressure Sender / Switch Connector</option>
        <option>Oxygen (O2) Sensor Connector</option>
        <option>Steering Angle Sensor Connector</option>
        <option>Temperature Sender / Sensor Connector</option>
        <option>Trailer Connector</option>
        <option>Vapor Canister Purge Valve Connector</option>
        <option>Vapor Canister Vent Solenoid Connector</option>
        <option>Wiper Switch Connector</option>
      </select>
    </div> 
    
    
    <div class="parts-select-block">
      <h4>Electrical-Switch &amp; Relay</h4>
      <select>
        <option>A/C Compressor Relay</option>
        <option>A/C and Heater Control Switch</option>
        <option>Accelerator Relay</option>
        <option>Accessory Delay Relay</option>
        <option>Accessory Power Relay</option>
        <option>Back Up Lamp Switch</option>
        <option>Blower Switch</option>
        <option>Brake Light Switch</option>
        <option>Clutch Pedal Position / Starter Safety Switch</option>
        <option>Cruise Control Switch</option>
        <option>Dimmer Switch</option>
        <option>Driving Light Relay</option>
        <option>Electronic Brake Control Relay</option>
        <option>Engine Control Module Wiring Relay</option>
        <option></option>
        <option>Fog Lamp Switch</option>
        <option>Fuel Pump / Circuit Opening Relay</option>
        <option>Hazard Warning Switch</option>
        <option>Headlamp Relay</option>
        <option>Headlamp Switch</option>
        <option>Ignition Relay</option>
        <option>Ignition Starter Relay</option>
        <option>Ignition Starter Switch</option>
        <option>Instrument Panel Dimmer Switch</option>
        <option>Multi-Function Switch</option>
        <option>Neutral Safety Switch / Range Sensor</option>
        <option>Oil Pressure Sender / Switch</option>
        <option>Parking Lamp Relay</option>
        <option>Power Seat Switch</option>
        <option>Power Window Switch</option>
        <option>Radiator Fan Relay</option>
        <option>Refrigerant Pressure Switch</option>
        <option>Secondary Air Injection Relay</option>
        <option>Steering Wheel Audio Control Switch</option>
        <option>Trunk Lid Release Switch</option>
        <option>Trunk Release Relay</option>
        <option>Turn Signal Relay</option>
        <option>Turn Signal Switch</option>
        <option>Wiper / Washer Switch</option>
        <option>Wiper Motor Relay</option>
      </select>
    </div>
      
    <div id="ignition" class="parts-select-block">
      <h4>Ignition</h4>
      <select>
        <option>Camshaft Position Sensor</option>
        <option>Crankshaft Position Sensor</option>
        <option>Ignition Coil</option>
        <option>Ignition Coil Wiring Harness Repair Kit</option>
        <option>Spark Plug</option>
        <option>Spark Plug Tube Seal</option>
      </select>
    </div> 
      
    
    <div id="engine" class="parts-select-block">
      <h4>Engine</h4>
      <select>
        <option>Camshaft</option>
        <option>Camshaft Repair Sleeve</option>
        <option>Camshaft Seal</option>
        <option>Conversion (Lower) Gasket Set</option>
        <option>Crankshaft Repair Sleeve</option>
        <option>Crankshaft Seal</option>
        <option>Cylinder Head Bolt</option>
        <option>Cylinder Head Gasket / Head Gasket Set</option>
        <option>Engine Cover</option>
        <option>Harmonic Balancer</option>
        <option>Intake Manifold</option>
        <option>Intake Manifold Gasket</option>
        <option>Motor Mount</option>
        <option>Oil Cooler</option>
        <option>Oil Cooler Line</option>
        <option>Oil Cooler Seal</option>
        <option>Oil Drain Plug</option>
        <option>Oil Drain Plug Gasket</option>
        <option>Oil Filler Cap</option>
        <option>Oil Filter</option>
        <option>Oil Filter Cover</option>
        <option>Oil Filter Gasket</option>
        <option>Oil Pan</option>
        <option>Piston Ring</option>
        <option>Timing Chain</option>
        <option>Timing Chain Tensioner</option>
        <option>Timing Cover Gasket Set</option>
        <option>Timing Cover Seal</option>
        <option>Timing Set</option>
        <option>Torque Strut Mount</option>
        <option>Turbocharger</option>
        <option>Turbocharger Boost Sensor</option>
        <option>Turbocharger Bypass Valve</option>
        <option>Turbocharger Oil Line Gasket</option>
        <option>Valve Cover Gasket</option>
        <option>Valve Lifter</option>
        <option>Valve Stem Seal</option>
        <option>Variable Timing Solenoid Gasket</option>
        <option>Variable Valve Timing Solenoid / Actuator</option>
      </select>
    </div> 
    
    <div id="exaust" class="parts-select-block">
      <h4>Exhaust &amp; Emission</h4>
      <select>
        <option>Catalytic Converter</option>
        <option>Clamp</option>
        <option>Exhaust Manifold Gasket</option>
        <option>Knock (Detonation) Sensor</option>
        <option>Mass Air Flow Sensor</option>
        <option>Muffler</option>
        <option>Oxygen (O2) Sensor</option>
        <option>PCV (Positive Crankcase Ventilation) Hose</option>
        <option>Pipe Flange Gasket / Seal</option>
        <option>Resonator</option>
        <option>Tail Pipe</option>
        <option>Vapor Canister</option>
        <option>Vapor Canister Purge Valve / Solenoid</option>
        <option>Vapor Canister Vent Valve / Solenoid</option>
      </select>
    </div> 
    <div id="fuel-air" class="parts-select-block">
      <h4>Fuel &amp; Air</h4>
      <select>
        <option>Air Filter</option>
        <option>Fuel Injection Pressure Damper</option>
        <option>Fuel Injector</option>
        <option>Fuel Injector O-Ring</option>
        <option>Fuel Level Sensor</option>
        <option>Fuel Pressure Sensor</option>
        <option>Fuel Pump &amp; Housing Assembly</option>
        <option>Fuel Pump Drive Module</option>
        <option>Fuel Pump Tank Seal</option>
        <option>Fuel Sending Unit Retainer</option>
        <option>Fuel Tank Cap</option>
        <option>Fuel Tank Cap Tester Adapter</option>
        <option>Fuel Tank Filler Neck</option>
        <option>Fuel Tank Pressure Sensor</option>
        <option>Fuel Tank Strap</option>
        <option>Intercooler</option>
        <option>Manifold Pressure (MAP) Sensor</option>
        <option>Throttle Body</option>
        <option>Throttle Body Gasket</option>
      </select>
    </div> 
    
    
    <div id="heat-airconditioning" class="parts-select-block">
      <h4>Heat &amp; Air Conditioning</h4>
      <select>
        <option>A/C Accumulator Hose Seal</option>
        <option>A/C Clutch Switch O-Ring</option>
        <option>A/C Compressor</option>
        <option>A/C Compressor &amp; Component Kit</option>
        <option>A/C Compressor Hose Seal</option>
        <option>A/C Compressor Service Valve / Adapter</option>
        <option>A/C Compressor Service Valve Cap</option>
        <option>A/C Condenser</option>
        <option>A/C Condenser Tube O-Ring</option>
        <option>A/C Evaporator Core</option>
        <option>A/C Evaporator Tube O-Ring</option>
        <option>A/C Expansion Valve</option>
        <option>A/C Expansion Valve Seal</option>
        <option>A/C Manifold Seal</option>
        <option>A/C Receiver Drier / Accumulator</option>
        <option>A/C Receiver Drier Desiccant Element</option>
        <option>A/C Refrigerant Hose</option>
        <option>A/C Refrigerant Pressure Sensor</option>
        <option>A/C Refrigerant Temperature Sensor</option>
        <option>Ambient Air Quality Sensor</option>
        <option>Ambient Air Temperature Sensor</option>
        <option>Blower Motor</option>
        <option>Blower Motor Control Module / Resistor</option>
        <option>Cabin Air Filter</option>
        <option>Cabin Air Temperature Sensor</option>
        <option>Climate Control Sun Sensor</option>
        <option>Heater / Evaporator Case Drain Hose</option>
        <option>Heater Air Inlet Door Actuator Gear</option>
        <option>Heater Blend Door Actuator</option>
        <option>Heater Core</option>
        <option>Heater Core Seal</option>
        <option>Heater Hose / Pipe</option>
        <option>Heater Mode Door Actuator Gear</option>
        <option>Humidity Sensor</option>
      </select>
    </div>  
    
      
    <div id="interior" class="parts-select-block">
      <h4>Interior</h4>
      <select>
        <option>Accelerator Pedal Position Sensor</option>
        <option>Hood Release Cable</option>
        <option>Inside Door Handle</option>
        <option>Radio Module Interface</option>
        <option>Speaker</option>
        <option>Speaker Amplifier</option>
        <option>Sunroof Motor</option>
        <option>Window Motor</option>
        <option>Window Regulator</option>
        <option>Window Regulator &amp; Motor Assembly</option>
      </select>
    </div> 
    
    <div id="steering" class="parts-select-block">
      <h4>Steering</h4>
      <select>
        <option>Power Steering Cooler Mount</option>
        <option>Rack and Pinion Complete Unit</option>
        <option>Steering Wheel Position Sensor</option>
        <option>Tie Rod End</option>
      </select>
    </div> 
    
    
    <div id="suspension" class="parts-select-block">
      <h4>Suspension</h4>
      <select>
        <option>Alignment Shim</option>
        <option>Bell Crank</option>
        <option>Caster / Camber Cam Bolt Kit</option>
        <option>Coil Spring Seat / Insulator</option>
        <option>Control Arm</option>
        <option>Control Arm Bushing</option>
        <option>Shock Absorber</option>
        <option>Shock Mount</option>
        <option>Stabilizer Bar Bushing</option>
        <option>Stabilizer Bar Link</option>
        <option>Strut</option>
        <option>Strut / Coil Spring / Mount Assembly</option>
        <option>Strut Bellow</option>
        <option>Strut Mount</option>
        <option>Strut Rod Lock Nut</option>
        <option>Suspension Kit</option>
        <option>Watts Link</option>
      </select>
    </div> 
    <div id="transmission-automatic" class="parts-select-block">
      <h4>Transmission-Automatic</h4>
      <select>
        <option>Automatic Transmission Assembly</option>
        <option>Bushings</option>
        <option>Clutch Pack Piston</option>
        <option>Clutch Pack Piston Return Spring</option>
        <option>Clutch Plate</option>
        <option>Conductor Plate</option>
        <option>Dipstick / Tube</option>
        <option>Dipstick / Tube Lock Pin</option>
        <option>Extension Housing Seal</option>
        <option>Filter</option>
        <option>Fluid Pump Body</option>
        <option>Fluid Pump Bushing</option>
        <option>Fluid Pump O-Ring</option>
        <option>Fluid Pump Seal</option>
        <option>Input Shaft Bearing</option>
        <option>Oil Pan</option>
        <option>Oil Pan Gasket</option>
        <option>Output Shaft Bearing</option>
        <option>Plug Adapter</option>
        <option>Rebuild Kit</option>
        <option>Seal</option>
        <option>Shift Improvement Kit</option>
        <option>Shift Solenoid</option>
        <option>Shims &amp; Spacers</option>
        <option>Sprag</option>
        <option>Stator Shaft Bushing</option>
        <option>Torque Converter</option>
        <option>Torque Converter Clutch Solenoid</option>
        <option>Transmission Fluid</option>
        <option>Transmission Mount</option>
        <option>Valve Body</option>
      </select>
    </div>
      
    
    <div id="transmission-manual" class="parts-select-block">
      <h4>Transmission-Manual</h4>
      <select>
        <option>Clutch Kit</option>
        <option>Clutch Master Cylinder</option>
        <option>Clutch Slave Cylinder</option>
        <option>Input Shaft Bearing</option>
        <option>Main / Output Shaft Repair Sleeve</option>
        <option>Main / Output Shaft Seal</option>
        <option>Shift Shaft Seal</option>
        <option>Transmission Mount</option>
        <option>Transmission Mount Bushing</option>
      </select>
    </div> 
    
    <div id="wheel" class="parts-select-block">
      <h4>Wheel</h4>
      <select>
        <option>Lug Nut</option>
        <option>Lug Nut Cover</option>
        <option>Lug Stud</option>
        <option>Tire Pressure Monitoring System (TPMS) Sensor</option>
        <option>Tire Pressure Monitoring System (TPMS) Service Kit</option>
        <option>Wheel</option>
      </select>
    </div> 
    
    
    <div id="wiper" class="parts-select-block">
      <h4>Wiper &amp; Washer</h4>
      <select>
        <option>Windshield Washer Fluid Reservoir</option>
        <option>Windshield Washer Hose</option>
        <option>Windshield Washer Nozzle</option>
        <option>Windshield Washer Pump</option>
        <option>Wiper Blade</option>
        <option>Wiper Linkage / Transmission</option>
        <option>Wiper Motor</option>
      </select>
    </div> 
  </div><!-- End tabpanel-->
</div><!-- End tab-content -->


</div><!--End Modal-Body-->
			<div class="modal-footer">
			  <button id="part_back" class="button continue-button" type="submit" name="car_model">Back</button>
				<button id="part_continue" class="button continue-button" data-status="step1" type="submit" name="car_model">Next</button>
				<button id="part_model_close" type="button" class="button continue-button" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">Close</span></button>
			</div><!-- End Modal Footer-->
		</div>
	</div>
</div>


// WoWTag - Dynamic World of Warcraft Character Data Parser and Display 0.1B

// Copyright 2010 Tim Hely
// http://tims-world.com/

// Released under GPL license
// Based on code by Jeroen Smeets http://jeroensmeets.net/
// Requires PHP Simple HTML DOM Parser: http://sourceforge.net/projects/simplehtmldom/

function wowTag()
{

	var _realm;
	var _character;
	var _refreshtime 	= 0; // 0 disables refresh (recommended)
	var _placeholder	= 'wowtag';
	var _debug 			= true;
	var _backcolor		= 'rgba(0,0,0,0.33)';
	var _border			= '1px #404040 solid';
	var _roundamt		= 5;
	var _shine			= "yes";
	var _namecolor		= "#fff";
	var _titlecolor		= "#e0e0e0";
	var _guildcolor		= "#ffb100";
	
	var _default = {
		realm: '',
		charname: '',
		placeholder: _placeholder,
		refreshtime: _refreshtime,
		debug: _debug,
		backcolor: _backcolor,
		border: _border,
		roundamt: _roundamt,
		shine: _shine,
		namecolor: _namecolor,
		titlecolor: _titlecolor,
		guildcolor: _guildcolor
	}
	
	var _pathname = '';
	var _phpScript = "wowtag.php";
	var _baseURL = "http://us.battle.net/wow/en/character/";
	
	function _logStatus(text) {
		if (_debug)
		  if ('undefined' != typeof console)
			if ('function' == typeof console.log)
			  if ('object' == typeof text)
				console.log(text);
			  else
				console.log('wowtag.js: ' + text);
	};
	
	function _getCharData() {
		jQuery.post(_pathname + _phpScript, {url: _baseURL + _realm + '/' + _character + '/simple' } ,
			function(data) {
				_processCharData(data);
			},
			'json'
		);
	};
	
	
	
	function _processCharData(data) {

		jQuery("div#" + _placeholder).html('');
		jQuery("div#" + _placeholder).addClass('wow-character');
		jQuery("div#" + _placeholder).css({
			'background-color' : _backcolor,
			'border' : _border,
			'-webkit-border-radius' : _roundamt + 'px',
			'-moz-border-radius' : _roundamt + 'px',
			'border-radius' : _roundamt + 'px'
		});
		jQuery("div#" + _placeholder).append('<a href="' + _baseURL + _realm + '/' + _character + '/simple" class="wow-link"><span class="wow-av-top wow-av-top-off"></span><img class="wow-avatar" src="' + data.card + ' height="47" width="47" /><div class="wow-name">' + data.name + '</div><div class="wow-title-guild"><div class="wow-title">' + data.title + '</div><div class="wow-guild">' + data.guild + '</div></div><div class="wow-under-name ' + data.color + '"><span class="wow-level"><strong>' + data.level + '</strong></span> ' + data.race + ' ' + data.spec + ' ' + data.class + ', <span class="wow-realm">' + data.realm + '</span><div class="wow-button wow-button-off"></div></a>');
		
		jQuery("div#" + _placeholder + " div.wow-name").css("color", _namecolor);
		jQuery("div#" + _placeholder + " div.wow-title").css("color", _titlecolor);
		jQuery("div#" + _placeholder + " div.wow-guild").css("color", _guildcolor);
					
		if (data.side=='H') jQuery('span.wow-realm').addClass('horde');
		else jQuery('span.wow-realm').addClass('alliance');
		
		jQuery("div#" + _placeholder + " a").hover(
			function() {
				jQuery(this).children(".wow-av-top").removeClass('wow-av-top-off');
				jQuery(this).children(".wow-button").removeClass('wow-button-off');
				jQuery(this).children(".wow-av-top").addClass('wow-av-top-on');
				jQuery(this).children(".wow-button").addClass('wow-button-on');
				
			},
			function() {
				jQuery(this).children(".wow-av-top").removeClass('wow-av-top-on');
				jQuery(this).children(".wow-button").removeClass('wow-button-on');
				jQuery(this).children(".wow-av-top").addClass('wow-av-top-off');
				jQuery(this).children(".wow-button").addClass('wow-button-off');
			}
		);
		
		if(_shine!="yes") jQuery("div#" + _placeholder + " a.wow-link span.wow-av-top").hide();
		
		if (_refreshtime > 0) {
			setTimeout('wowTag.refreshData();', _refreshtime * 60000);
		}
		jQuery("div#" + _placeholder).fadeIn('slow');
	};
	
	function _getID(cname, i)
	{
		var candidate = "wow-" + cname;
		if (i)
			candidate += i;
		else
			i = 0;
		if (document.getElementById(candidate))
			return _getID(cname, i+1);
		else
			return candidate;
	};
	
	function getSettings(_config)
	{
		var _settings = _default;
		if(_config)
		{
			if(_config.realm) { _settings.realm = _config.realm }
			if(_config.charname) { _settings.charname = _config.charname }
			if(_config.placeholder) { _settings.placeholder = _config.placeholder }
			if(_config.refreshtime) { _settings.refreshtime = _config.refreshtime }
			if(_config.debug) { _settings.debug = _config.debug }
			if(_config.backcolor) { _settings.backcolor = _config.backcolor }
			if(_config.border) { _settings.border = _config.border }
			if(_config.roundamt) { _settings.roundamt = _config.roundamt }
			if(_config.shine) { _settings.shine = _config.shine }
			if(_config.namecolor) { _settings.namecolor = _config.namecolor }
			if(_config.titlecolor) { _settings.titlecolor = _config.titlecolor }
			if(_config.guildcolor) { _settings.guildcolor = _config.guildcolor }
		}
		return _settings;
	}
	
	
	return {
		
		setRealm: function(orRealm) {
			_realm = orRealm;
		},
		
		setCharacter: function(orCharName) {
			_character = orCharName;
		},
		
		setBackcolor: function(orBackcolor) {
			_backcolor = orBackcolor;
		},
		
		setBorder: function(orBorder) {
			_border = orBorder;
		},
		
		setRoundamt: function(orRoundamt) {
			_roundamt = orRoundamt;
		},
		
		setShine: function(orShine) {
			_shine = orShine;
		},
		
		setNamecolor: function(orNamecolor) {
			_namecolor = orNamecolor;
		},
		
		setGuildcolor: function(orGuildcolor) {
			_guildcolor = orGuildcolor;
		},
		
		setTitlecolor: function(orTitlecolor) {
			_titlecolor = orTitlecolor;
		},
		
		setRefreshTime: function(orRefreshTime) {
			var _pI = parseInt(orRefreshTime);
			if (_pI > 0) {
				_refreshtime = _pI;
			}
		},
		
		setPlaceholder: function(orPlaceholder) {
			_placeholder = orPlaceholder;
		},
		
		init: function(_config) {
			_logStatus('initializing');
			var _settings = getSettings(_config);
			
			_pathname = jQuery("script[src$='wowtag.js']").attr('src');
			_pathname = _pathname.substr(0, _pathname.length - 9);
			if(jQuery("link[href$='wowtag.css']").length < 1)
			{
				_logStatus('Adding CSS...');
				jQuery('head').append('<link rel="stylesheet" href="' + _pathname + 'wowtag.css" type="text/css" />');
			}
			
			if (_settings.placeholder)  { this.setPlaceholder(_settings.placeholder); }
			
			if (jQuery("div#" + _placeholder).length < 1) {
				_logStatus('error: placeholder for character not found');
				return false;
			}
			jQuery("div#" + _placeholder).append('<div class="wow-character"><div class="wow-load"></div></div>');
			jQuery("div#" + _placeholder).children('div.wow-load').hide();
			jQuery("div#" + _placeholder).children('div.wow-load').fadeIn('fast');
			if (_settings.charname)		{ this.setCharacter(_settings.charname) }
			if (_settings.realm)		{ this.setRealm(_settings.realm) }
			if (_settings.backcolor)	{ this.setBackcolor(_settings.backcolor) }
			if (_settings.border)	{ this.setBorder(_settings.border) }
			if (_settings.roundamt)		{ this.setRoundamt(_settings.roundamt) }
			if (_settings.shine) 		{ this.setShine(_settings.shine) }
			if (_settings.namecolor)	{ this.setNamecolor(_settings.namecolor) }
			if (_settings.titlecolor)	{ this.setTitlecolor(_settings.titlecolor) }
			if (_settings.guildcolor)	{ this.setGuildcolor(_settings.guildcolor) }
			_getCharData();
		},
		
		refreshData: function() {
			_getCharData();
		},
		
		processCharData: function(data) {
			_processCharData(data);
		},
		
		grab: function(_config) {
			var _settings = getSettings(_config);
			var _regex = /\[wowtag\|.+\|.+\]/;
			var _match = document.body.innerHTML.match(_regex);
			if (_match) {
				_match = _match[0].replace('[', '').replace(']', '').split('|');
				_logStatus('Hey, that\'s nice, this site is using the [wowtag|realm|charname] method.');
				if (_match[1]) {
				  _settings.realm = _match[1];
				  _logStatus('Changing realm to ' + _match[1]);
				}
				if (_match[2]) {
				  _settings.charname = _match[2];
				  _logStatus('Changing character name to ' + _match[2]);
				}
				_placeholder = _getID(_settings.charname);
				_settings.placeholder = _placeholder;
				document.body.innerHTML = document.body.innerHTML.replace(_regex, '<div id=' + _placeholder + '></div>');
			}
			this.init(_settings);
		}
		
	};
};
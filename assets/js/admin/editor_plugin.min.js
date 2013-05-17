(
	function(){
		tinymce.create(
			"tinymce.plugins.ColorShopShortcodes",
			{
				init: function(d,e) {},
				createControl:function(d,e)
				{

					var ed = tinymce.activeEditor;

					if(d=="colorshop_shortcodes_button"){

						d=e.createMenuButton( "colorshop_shortcodes_button",{
							title: ed.getLang('colorshop.insert'),
							icons: false
							});

							var a=this;d.onRenderMenu.add(function(c,b){

								a.addImmediate(b, ed.getLang('colorshop.order_tracking'),"[colorshop_order_tracking]" );
								a.addImmediate(b, ed.getLang('colorshop.price_button'), '[add_to_cart id="" sku=""]');
								a.addImmediate(b, ed.getLang('colorshop.product_by_sku'), '[product id="" sku=""]');
								a.addImmediate(b, ed.getLang('colorshop.products_by_sku'), '[products ids="" skus=""]');
								a.addImmediate(b, ed.getLang('colorshop.product_categories'), '[product_categories number=""]');
								a.addImmediate(b, ed.getLang('colorshop.products_by_cat_slug'), '[product_category category="" per_page="12" columns="4" orderby="date" order="desc"]');

								b.addSeparator();

								a.addImmediate(b, ed.getLang('colorshop.recent_products'), '[recent_products per_page="12" columns="4" orderby="date" order="desc"]');
								a.addImmediate(b, ed.getLang('colorshop.featured_products'), '[featured_products per_page="12" columns="4" orderby="date" order="desc"]');

								b.addSeparator();

								a.addImmediate(b, ed.getLang('colorshop.shop_messages'), '[colorshop_messages]');

								b.addSeparator();

								c=b.addMenu({title:"Pages"});
										a.addImmediate(c, ed.getLang('colorshop.cart'),"[colorshop_cart]" );
										a.addImmediate(c, ed.getLang('colorshop.checkout'),"[colorshop_checkout]" );
										a.addImmediate(c, ed.getLang('colorshop.my_account'),"[colorshop_my_account]" );
										a.addImmediate(c, ed.getLang('colorshop.edit_address'),"[colorshop_edit_address]" );
//										a.addImmediate(c, ed.getLang('colorshop.change_password'),"[colorshop_change_password]" );
										a.addImmediate(c, ed.getLang('colorshop.change_email_and_password'),"[colorshop_account_setting]" );
										a.addImmediate(c, ed.getLang('colorshop.view_order'),"[colorshop_view_order]" );
										a.addImmediate(c, ed.getLang('colorshop.pay'),"[colorshop_pay]" );
										a.addImmediate(c, ed.getLang('colorshop.thankyou'),"[colorshop_thankyou]" );

							});
						return d

					} // End IF Statement

					return null
				},

				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}

			}
		);

		tinymce.PluginManager.add( "ColorShopShortcodes", tinymce.plugins.ColorShopShortcodes);
	}
)();
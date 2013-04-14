/**
 * Ajax.Form
 * Builds/sends a request based on all elements of the given form.
 * Be sure to specify the action and method in the form tag as the request will pull from it
 * @todo may be impossible but find a way to upload images as part of the request.
 *
 */

Ajax.Form = Class.create();
Object.extend(Object.extend(Ajax.Form.prototype, Ajax.Request.prototype), {
	initialize: function (form, options)
	{
		var form = $(form);
		if(!form) return false;
		if(form.tagName.toLowerCase() != 'form') return false; // Only <FORM> elements can be used
		this.form = form;
		this.transport = Ajax.getTransport();
		this.setOptions(options);
		this.options.method = this.form.method || 'post';
		this.setParameters();
		this.request(this.form.action);
	},
	setParameters: function()
	{
		this.options.parameters = Form.serialize(this.form);
	}
});
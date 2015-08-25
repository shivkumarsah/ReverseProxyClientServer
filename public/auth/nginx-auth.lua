local cookies = ngx.header.set_cookie
if not cookies then
	--ngx.header.set_cookie = "PHPSESSID=SHIV9876543210"
	local gws_token = ngx.var.gws
	if gws_token ~= "" then
		local new_cookies = {}
		table.insert(new_cookies, "s=k;path=/;")
		table.insert(new_cookies, "gws=" .. gws_token .. "; path=/;")
		ngx.header.set_cookie = new_cookies
	end
	return
end
local nginx_gws = ngx.var.gws
if nginx_gws then
	local newcookies = {}
	table.insert(newcookies, "newKey=newVal;path=/;,")
	table.insert(newcookies, "gws=" .. nginx_gws .. ";path=/;,")
	if type(cookies)~="table" then cookies = {cookies} end
	if type(cookies)=="table" then
		for i, val in ipairs(cookies) do
			--local newval = string.gsub(val, "([dD]omain)=[%w_-\\\\.]+", "%1=.com")
			local newval = val
			table.insert(newcookies, newval)
		end
	else
		table.insert(newcookies,cookies)
		--table.insert(newcookies,"shiv=kumar")
	end
	ngx.header.set_cookie = newcookies
end
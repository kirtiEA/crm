(function(){dust.register("campaigns",body_0);function body_0(chk,ctx){return chk.section(ctx._get(true,[]),ctx,{"block":body_1},null);}function body_1(chk,ctx){return chk.write("<li class=\"list-item\"><h2 class=\"list-item-heading clickfor-show-hide pull-left\"><span class=\"glyphicon glyphicon-plus expand-collapse\" onclick=\"fetchCampaignDetails('").reference(ctx._get(false, ["id"]),ctx,"h").write("');\"></span>&nbsp;").reference(ctx._get(false, ["name"]),ctx,"h").write(" (").reference(ctx._get(false, ["count"]),ctx,"h").write(")</h2><h3>&nbsp;&nbsp;").reference(ctx._get(false, ["startDate"]),ctx,"h").write(" - ").reference(ctx._get(false, ["endDate"]),ctx,"h").write("</h3><div class=\"pull-right\"><button class=\"btn btn-secondary\"><span class=\"glyphicon glyphicon-share\"></span> Share</button>&nbsp;<button class=\"btn btn-secondary\" data-toggle=\"modal\" data-target=\"#add-site-modal\" onclick=\"fetchvendors('").reference(ctx._get(false, ["name"]),ctx,"h").write("', '").reference(ctx._get(false, ["id"]),ctx,"h").write("');\"><span class=\"glyphicon glyphicon-plus\"></span> Add Sites</button>&nbsp;<button class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#\" onclick=\"saveCampaign('").reference(ctx._get(false, ["id"]),ctx,"h").write("');\">Save Campaign</button></div><div class=\"list-item-content show-hide-content\"><ul class=\"sub-list\" id=\"campaign_").reference(ctx._get(false, ["id"]),ctx,"h").write("\"></ul></div></li>");}return body_0;})();
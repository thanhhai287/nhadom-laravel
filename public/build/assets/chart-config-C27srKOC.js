$(document).ready(function(){const h={id:"drawColumnDataNumber",afterDatasetsDraw:function(t,i,f){var l=t.ctx;t.data.datasets.forEach(function(o,s){var r=t.getDatasetMeta(s);r.hidden||r.data.forEach(function(a,e){if(!(o.data[e]<=0)){l.fillStyle="rgb(0,0,0)";var n=16,g="normal",C="Helvetica Neue";l.font=Chart.helpers.fontString(n,g,C);var c=d(o.data[e]).toString();l.textAlign="center",l.textBaseline="middle";var u=a.tooltipPosition();l.fillText(c,u.x,u.y)}})})}};var d=function(t){return(t/1).toFixed(0).replace(".",",").toString().replace(/\B(?=(\d{3})+(?!\d))/g,".")};let v=document.getElementById("salesPurchasesChart");$.get("/sales-purchases/chart-data",function(t){var i=[],f=new Date().getDate();new Date().getMonth();for(var l=1;l<=f;l++)i.push(l);var o=[],s=[],r=[];t.sales.original.data.forEach(function(a,e){var n=new Date(a.date).getDate();o[n]?o[n]+=parseInt(t.sales.original.data[e].count)/100:o[n]=parseInt(t.sales.original.data[e].count)/100,r[n]||(r[n]={}),r[n][a.payment_method]=parseInt(a.count)/100}),console.log(r),$.each(i,function(a,e){s[a]=a+1,o[a]=o[a+1]||0,r[a]=r[a+1]||{"Bank Transfer":0,Cash:0}}),console.log(o),new Chart(v,{type:"bar",data:{labels:s,datasets:[{label:"Doanh thu",data:o,customData:r,backgroundColor:["#6366F1"],borderColor:["#6366F1"],borderWidth:1}]},options:{animation:{duration:500,easing:"easeOutQuart",onComplete:function({chart:a}){const e=a.ctx;e.textAlign="center",e.textBaseline="bottom",a.config.data.datasets.forEach(function(n,g){a.getDatasetMeta(g).data.forEach(function(c,u){const m=n.data[u];m>0&&e.fillText(d(m),c.x,c.y-5)})})}},scales:{x:{display:!0},y:{stacked:!0}},plugins:{tooltip:{callbacks:{label:function(a){var e=a.dataset.customData[a.dataIndex],n=a.dataset.data[a.dataIndex];return`Tổng: ${d(n)} | Tiền mặt: ${d(e.Cash)} | Chuyển khoản: ${d(e["Bank Transfer"])}`}}}}}})});let b=document.getElementById("currentDailyChart"),y=document.getElementById("currentMonthChart");$.get("/current-month/chart-data",function(t){console.log(t),new Chart(b,{type:"doughnut",data:{labels:["Ca sáng","Ca chiều","Ca tối"],datasets:[{data:[t.morning,t.afternoon,t.night],backgroundColor:["#F59E0B","#0284C7","#EF4444"],hoverBackgroundColor:["#F59E0B","#0284C7","#EF4444"]}]},plugins:[h]}),new Chart(y,{type:"doughnut",data:{labels:["Ca sáng","Ca chiều","Ca tối"],datasets:[{data:[t.morningMonth,t.afternoonMonth,t.nightMonth],backgroundColor:["#F59E0B","#0284C7","#EF4444"],hoverBackgroundColor:["#F59E0B","#0284C7","#EF4444"]}]},plugins:[h]})})});

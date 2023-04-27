$(function(){

   $("#sparkline").sparkline([5,6,7,3,5,5,3,2,7,4,6,7], {
    type: 'line',
    width:'100',
    height:'50',
    lineColor: '#3bb1ed'});
  $("#sparkbar").sparkline([5,6,7,3,5,5,3,2,7,4,6,7], {
    type: 'bar',
    width:'100',
    height:'50',
    lineColor: '#3bb1ed'});
$("#sparktristate").sparkline([1,1,0,1,-1,-1,1,-1,0,0,1,1], {
    type: 'tristate',height:'50'});
$("#discrete").sparkline([4,6,7,7,4,3,2,1,4,4], {
    type: 'discrete'});
$("#bullet").sparkline([4,6,7,7,4,3,2,1,4,4], {
    type: 'bullet'});
$("#pie").sparkline([2,1,4,4], {
    type: 'pie'});
$("#box").sparkline([4,6,7,7,4,3,2,1,4,4], {
    type: 'box'});
});


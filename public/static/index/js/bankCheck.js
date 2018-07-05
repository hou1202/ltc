/**
 * Created by Administrator on 2018/7/3.
 */
function luhnCheck(bankno){
          var lastNum=bankno.substr(bankno.length-1,1);//取出最后一位（与luhn进行比较）

          var first15Num=bankno.substr(0,bankno.length-1);//前15或18位
          var newArr=new Array();
          for(var i=first15Num.length-1;i>-1;i--){    //前15或18位倒序存进数组
                  newArr.push(first15Num.substr(i,1));
          }
          var arrJiShu=new Array();  //奇数位*2的积 <9
          var arrJiShu2=new Array(); //奇数位*2的积 >9

          var arrOuShu=new Array();  //偶数位数组
          for(var j=0;j<newArr.length;j++){
              if((j+1)%2==1){//奇数位
                  if(parseInt(newArr[j])*2<9)
                      arrJiShu.push(parseInt(newArr[j])*2);
                  else
                      arrJiShu2.push(parseInt(newArr[j])*2);
              }
              else //偶数位
                  arrOuShu.push(newArr[j]);
              }

          var jishu_child1=new Array();//奇数位*2 >9 的分割之后的数组个位数
          var jishu_child2=new Array();//奇数位*2 >9 的分割之后的数组十位数
          for(var h=0;h<arrJiShu2.length;h++){
              jishu_child1.push(parseInt(arrJiShu2[h])%10);
              jishu_child2.push(parseInt(arrJiShu2[h])/10);
          }

          var sumJiShu=0; //奇数位*2 < 9 的数组之和
          var sumOuShu=0; //偶数位数组之和
          var sumJiShuChild1=0; //奇数位*2 >9 的分割之后的数组个位数之和
          var sumJiShuChild2=0; //奇数位*2 >9 的分割之后的数组十位数之和
          var sumTotal=0;
          for(var m=0;m<arrJiShu.length;m++){
              sumJiShu=sumJiShu+parseInt(arrJiShu[m]);
          }

          for(var n=0;n<arrOuShu.length;n++){
              sumOuShu=sumOuShu+parseInt(arrOuShu[n]);
          }

          for(var p=0;p<jishu_child1.length;p++){
              sumJiShuChild1=sumJiShuChild1+parseInt(jishu_child1[p]);
              sumJiShuChild2=sumJiShuChild2+parseInt(jishu_child2[p]);
          }
          //计算总和
          sumTotal=parseInt(sumJiShu)+parseInt(sumOuShu)+parseInt(sumJiShuChild1)+parseInt(sumJiShuChild2);

          //计算luhn值
          var k= parseInt(sumTotal)%10==0?10:parseInt(sumTotal)%10;
          var luhn= 10-k;

          if(lastNum==luhn){
              console.log("验证通过");
              return true;
          }else{
              layer.msg("银行卡号必须符合luhn校验");
              return false;
          }
}

  //检查银行卡号
function CheckBankNo(bankno) {
          var bankno = bankno.replace(/\s/g,'');
          if(bankno == "") {
              layer.msg("请填写银行卡号");
              return false;
          }
          if(bankno.length < 16 || bankno.length > 19) {
              layer.msg("银行卡号长度必须在16到19之间");
              return false;
          }
          var num = /^\d*$/;//全数字
          if(!num.exec(bankno)) {
              layer.msg("银行卡号必须全为数字");
              return false;
          }
          //开头6位
          var strBin = "10,18,30,35,37,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,58,60,62,65,68,69,84,87,88,94,95,98,99";
          if(strBin.indexOf(bankno.substring(0, 2)) == -1) {
              layer.msg("银行卡号开头6位不符合规范");
              return false;
          }
          //Luhn校验
          if(!luhnCheck(bankno)){
              return false;
          }
          return true;
}
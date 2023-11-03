<html>
    <style>
    body {
  margin: 0;
  padding: 0;
}
      body:before{
  background-image:url('pexels-neo-2653362.jpg');
  content: '';
  position: fixed;
  width: 100vw;
  height: 100vh;
  background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
      -webkit-filter: blur(7px);
     -moz-filter: blur(7px);
    -o-filter: blur(7px);
    -ms-filter: blur(7px);
     filter: blur(7px);
      z-index: -9;
      /* width: auto; */
       /* margin:0;
       padding:0; */
      }
      table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 50%;
      }

      td {
        border: 1px solid #dddddd;
      }

    p {
      font-size:35px;font-family:Arial Narrow;font-style:italic:;color:white;
    }
D {
  font-size:20px;color:#09BC8A
}
E {
  font-size:15px;color:#74B3CE
}
PC
{
  font-size:20px;color:white;
}
MSG{
  font-size:20px;color:#03CEA4;
}
INST
{
  font-size:20px;color:#F39237
}
jump
{
  font-size:20px;color:#8B5FBF
}
num
{
  font-size:20px;color:#FFE900
}
inc {
  font-size:20px;color:#F93943
}
mul {
  font-size:20px;color:#44FFD2
}
i{
  float:left;
}
    </style>
    <header>
<script>
class Queue {
    constructor() {
        this.items = [];
    }

    // add element to the queue
    enqueue(element) {
        return this.items.push(element);
    }

    // remove element from the queue
    dequeue() {
        if(this.items.length > 0) {
            return this.items.shift();
        }
    }

    // view the last element
    peek() {
        return this.items[this.items.length - 1];
    }

    // check if the queue is empty
    isEmpty(){
       return this.items.length == 0;
    }

    // the size of the queue
    size(){
        return this.items.length;
    }

    // empty the queue
    clear(){
        this.items = [];
    }
}
var ftable="";
var retr1=0;
var h1=0; // number of instructions
var pc = -4; // pc starts from -4 to br incremeanted from zero
var Runpc=0;
var stins='<table style="float: left;border-collapse: collapse;border-spacing: 0;width: 50%; solid #ddd;"><tr><td><PC>PC</PC></td><td><PC>INSTRUCTIONS</PC></td></tr>'; // ststus string to be printed
var registers=[0,0,0,0,0,0,0];  // register values
var tagR=[0,0,0,0,0,0,0]; //register tags for read
var tagW=[0,0,0,0,0,0,0]; //register tags for write
var mem =new Array(32000); //memory array
var b_registers = [0,0,0,0,0,0,0];
mem.fill(0);
var HWLW=2; //hardware units for load ins
var HWSW=2;// the same for save word ins
var HWBEQ=1;//the same for branch
var HWJALR=1;//jalr/ret units
var HWARTH=2;// arithmatic operations unit
var HWMUL=1;//multiplication units
let q=new Queue();//excute queue
let q2=new Queue();//wait queue
let q3=new Queue();//wait helper
let executable = new Queue();
let executable_helper = new Queue();
var instarray=new Array(99); // array to carry instruction
instarray.fill("");
var par1array=new Array(99); /* array to save the first part */
par1array.fill(0);
var par2array=new Array(99); // array to save second part
par2array.fill(0);
var par3array=new Array(99); // array to save third part
par3array.fill(0);
var inscycles=new Array(99);//array to save number of cyles
inscycles.fill(0);
var issued=new Array(99);//array to save number of cyles
issued.fill(0);
var started=new Array(99);//array to save number of cyles
started.fill(0);
var ended=new Array(99);//array to save number of cyles
ended.fill(0);
var written=new Array(99);//array to save number of cyles
written.fill(0);
var t=0;
let qEx=new Queue(); //execution queue
var branch;
var issuebranch = -1;
var branch_correct_predictions;
var pcflag;
var writtenflag;
function run2(){
retr1=0;
 branch = 0;
 branch_correct_predictions = 0;
 writtenflag = 0;
document.getElementById('unit').setAttribute('disabled', true);
document.getElementById('submit3').setAttribute('disabled', true);
document.getElementById('changesub').setAttribute('disabled', true);
document.getElementById('changeunit').setAttribute('disabled', true);
document.getElementById('unit').value = " ";
document.getElementById('changeunit').innerHTML = "Please Select";
Runpc=0;
t=0;
var indx=Runpc/4;// initial index
var nRun=0;
q.clear();
q2.clear();
q3.clear();
qEx.clear();
executable.clear();
executable_helper.clear();
 ftable="";
 registers=[0,0,0,0,0,0,0];  // register values
 tagR=[0,0,0,0,0,0,0,0]; //register tags for read
 tagW=[0,0,0,0,0,0,0,0]; //register tags for write
issued.fill(0);
started.fill(0);
ended.fill(0);
written.fill(0);
var pc_counter = 0;
while(instarray[pc_counter/4]!=""){
if (instarray[pc_counter/4]=="LW"){
 inscycles[pc_counter/4]=3;
}
else if (instarray[pc_counter/4]=="SW"){
 inscycles[pc_counter/4]=3;
}
else if (instarray[pc_counter/4]=="BEQ"){
 inscycles[pc_counter/4]=1;
}
else if(instarray[pc_counter/4]=="JALR"){
   inscycles[pc_counter/4]=1;
}
else if(instarray[pc_counter/4]=="RET"){
 inscycles[pc_counter/4]=2;
}
else if (instarray[pc_counter/4]=="ADD"){
 inscycles[pc_counter/4]=2;
 }
   else if (instarray[pc_counter/4]=="ADDI"){
 inscycles[pc_counter/4]=2;
}
else if (instarray[pc_counter/4]=="NEG"){
 inscycles[pc_counter/4]=2;
}
else if (instarray[pc_counter/4]=="MULL"){
 inscycles[pc_counter/4]=6;
}
else if (instarray[pc_counter/4]=="MULH"){
 inscycles[pc_counter/4]=6;
}
pc_counter = pc_counter+4;
}

while(instarray[indx]!=""||!q.isEmpty()||!q2.isEmpty()){//first 999 cycles// while there is  a new inst or q has running inst

 indx=Runpc/4;//updated index



 if (t > 99)
{
 Runpc=0;
 break;
}

var e = instarray[indx];
var par1=par1array[indx];
var par2=par2array[indx];
var par3=par3array[indx];
var cyc=inscycles[indx];

//issue stage

while(!q2.isEmpty() ){//start of q2 loop // to take from q2 when its instrunction parameters become ready

var ind = q2.dequeue();
var instrc=q2.dequeue();
if((instarray[ind]=="LW")&&HWLW>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWLW--;
 issued[ind]=t;
}
else if((instarray[ind]=="SW")&&HWSW>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWSW--;
 issued[ind]=t;
}
else if((instarray[ind]=="BEQ")&&HWBEQ>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWBEQ--;
 issued[ind]=t;
}
else if((instarray[ind]=="JALR")&&HWJALR>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWJALR--;
 issued[ind]=t;
}
else if(instarray[ind]=="RET"&&HWJALR>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWJALR--;
 issued[ind]=t;
}
else if((instarray[ind]=="ADD")&&HWARTH>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWARTH--;
 issued[ind]=t;
}
else if((instarray[ind]=="ADDI")&&HWARTH>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWARTH--;
 issued[ind]=t;
}
else if((instarray[ind]=="NEG")&&HWARTH>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWARTH--;
 issued[ind]=t;
}
else if((instarray[ind]=="MULL"||instarray[ind]=="MULH")&&HWMUL>0)
{
 q.enqueue(ind);
 q.enqueue(instarray[ind]);
 HWMUL--;
 issued[ind]=t;
}
else{
 q3.enqueue(ind);
 q3.enqueue(instrc);
}
}


while(!q3.isEmpty() ){// to take from q2 when its instrunction parameters become ready
var e=q3.dequeue();
q2.enqueue(e);
}

if((instarray[indx]=="LW")&&HWLW>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWLW--;
 issued[indx]=t;

}
else if((instarray[indx]=="SW")&&HWSW>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWSW--;
 issued[indx]=t;
}
else if((instarray[indx]=="BEQ")&&HWBEQ>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWBEQ--;
 issued[indx]=t;
}
else if((instarray[indx]=="JALR")&&HWJALR>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWJALR--;
 issued[indx]=t;
}
else if(instarray[indx]=="RET"&&HWJALR>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWJALR--;
 issued[indx]=t;
}
else if((instarray[indx]=="ADD")&&HWARTH>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWARTH--;
 issued[indx]=t;
}
else if((instarray[indx]=="ADDI")&&HWARTH>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWARTH--;
 issued[indx]=t;

}
else if((instarray[indx]=="NEG")&&HWARTH>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWARTH--;
 issued[indx]=t;

}
else if((instarray[indx]=="MULL"||instarray[indx]=="MULH")&&HWMUL>0)
{
 q.enqueue(indx);
 q.enqueue(instarray[indx]);
 HWMUL--;
 issued[indx]=t;

}
else if (instarray[indx]!=""){//default case for all

 q2.enqueue(indx);
 q2.enqueue(instarray[indx]);

}//end of issue stage
 Runpc+=4;
 while(!q.isEmpty()) // start execution stage and register status
 {
   var indx=q.dequeue();// q is a queue for running instructions
   var inst=q.dequeue();// if two instructions are finished the two will pass through the while loop with the same t and write the same t into ended array
   var par1=par1array[indx];
   var par2=par2array[indx];
   var par3=par3array[indx];
   if((tagW[par3]==0&&tagR[par1]==0)&&(instarray[indx]=="LW"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
     if(par1!=0){
     tagW[par1]=1;}
       if(par3!=0){
     tagR[par3]=1;}
   }
   else if((tagW[par3]==0&&tagW[par1]==0)&&(instarray[indx]=="SW"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
       if(par1!=0){
     tagR[par1]=1;}
       if(par3!=0){
     tagR[par3]=1;}

   }
   else if((tagW[par2]==0&&tagW[par1]==0)&&(instarray[indx]=="BEQ"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
       if(par1!=0){
     tagR[par1]=1;}
       if(par2!=0){
     tagR[par2]=1;}
   }
   else if((tagW[par1]==0)&&(instarray[indx]=="JALR"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
       if(par1!=0){
     tagR[par1]=1;}
   }
   else if(instarray[indx]=="RET")
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);

   }
   else if((tagW[par2]==0&&tagW[par3]==0&&tagR[par1]==0)&&(instarray[indx]=="ADD"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
       if(par1!=0){
     tagW[par1]=1;}
       if(par2!=0){
     tagR[par2]=1;}
       if(par3!=0){
     tagR[par3]=1;}
   }
   else if((tagW[par2]==0&&tagR[par1]==0)&&(instarray[indx]=="ADDI"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
       if(par1!=0){
     tagW[par1]=1;}
       if(par2!=0){
       tagR[par2]=1;}
   }
   else if((tagW[par2]==0&&tagR[par1]==0)&&(instarray[indx]=="NEG"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
       if(par1!=0){
     tagW[par1-1]=1;}
       if(par2!=0){
     tagR[par2]=1;}

   }
   else if((tagW[par2]==0&&tagW[par3]==0&&tagR[par1]==0)&&(instarray[indx]=="MULL"||instarray[indx]=="MULH"))
   {
     executable.enqueue(indx);
     executable.enqueue(instarray[indx]);
       if(par1!=0){
     tagW[par1]=1;}
       if(par2!=0){
     tagR[par2]=1;}
       if(par3!=0){
     tagR[par3]=1;}
}
   else {
     executable_helper.enqueue(indx);
     executable_helper.enqueue(inst);
   }
 }
 while(!executable_helper.isEmpty() ){
 var e=executable_helper.dequeue();
 q.enqueue(e);
 }
 while(!executable.isEmpty()){// the same t for the whole while loop
   var indx=executable.dequeue();// q is a queue for running instructions
   var inst=executable.dequeue();// if two instructions are finished the two will pass through the while loop with the same t and write the same t into ended array


   if(inscycles[indx]==0){//end run and write back
     ended[indx]=t;

     written[indx]=t+1;


     writtenflag = 1;


     var e = instarray[indx];
     var par1=par1array[indx];
     var par2=par2array[indx];
     var par3=par3array[indx];
     var cyc=inscycles[indx];
     if (e=="LW"){
       var r=par1;
       var imm=par2;
       var r2=par3;
       var loc;
       if(r2==0){
       loc=imm;
       }
       else {
         loc=registers[r2-1]+imm;
       }
       registers[r-1]=mem[loc];
       tagW[par1]=0;
       tagR[par3]=0;
       HWLW++;
     }
     if (e=="SW"){
       var r=par1;
       var imm=par2;
       var r2=par3;
       var loc
       if(r2==0){
       loc=imm;
       }
       else {
         loc=registers[r2-1]+imm;
       }
       mem[loc]=  registers[r-1];
  tagR[par1]=0;
   tagR[par3]=0;
   HWSW++;
     }
     if (e=="ADD"){
       var r1=par1;
       var r2=par2;
       var r3=par3;
       if(r2==0)
       {registers[r1-1]=registers[r3-1];}
       else   if(r3==0)
         {registers[r1-1]=registers[r2-1];}
         else{
           registers[r1-1]=registers[r2-1]+registers[r3-1];
         }
         tagW[par1]=0;
           tagR[par2]=0;
           tagR[par3]=0;
           HWARTH++;
       }
         if (e=="ADDI"){
           var r1=par1;
           var r2=par2;
           var imm=par3;
           tagW[par1]=0;
           tagR[par2]=0;
           HWARTH++;
           if(r2==0)
           {registers[r1-1]=imm;}

             else{
               registers[r1-1]=registers[r2-1]+imm;
             }

     }
     if (e=="NEG"){
       var r1=par1;
       var r2=par2;
       tagW[par1]=0;
       tagR[par2]=0;
   HWARTH++;
       if(r2==0)
       {
         registers[r1-1] = 0;
       }
       else{
       registers[r1-1]=((-1)*registers[r2-1]);
     }

     }
     if (e=="MULL"){
       var r1=par1;
       var r2=par2;
       var r3=par3;
       tagW[par1]=0;
       tagR[par2]=0;
       tagR[par3]=0;
       HWMUL++;
       if(r2==0||r3==0)
       {registers[r1-1]=0;}
       else{
       var num = registers[r2-1] * registers[r3-1];
       registers[r1-1]= Math.floor(num%(Math.pow(2,16)));
     }

     }
     if (e=="MULH"){
       var r1=par1;
       var r2=par2;
       var r3=par3;
       tagW[par1]=0;
       tagR[par2]=0;
       tagR[par3]=0;
       HWMUL++;
       if(r2==0||r3==0)
       {registers[r1-1]=0;}
       else{
       var num = registers[r2-1] * registers[r3-1];
       registers[r1-1]= Math.floor(num/(Math.pow(2,16)));
     }

     }
     if(e=="BEQ"){
       var r1=par1;
       var r2=par2;
       var imm=par3;
       branch++;
       if (r1==0|r2==0){
         if(r1==0)
         {
           if(r2==0||registers[r2-1]==0)
           {Runpc=(indx*4)+imm;
           branch_correct_predictions++;}
         }
         else if(r2==0)
         {
           if(r1==0||registers[r1-1]==0)
           {Runpc=(indx*4)+imm;
           branch_correct_predictions++;}
         }
       }
       else if(registers[r1-1]==registers[r2-1]){
         Runpc=(indx*4)+imm;
         branch_correct_predictions++;
       }
       tagR[par1]=0;
       tagR[par2]=0;
       HWBEQ++;
     }
     if(e=="JALR"){
       var r1=par1;
       retr1=indx*4+4;//separate register, when jalr is called , it save the address of pc in ra
       tagR[par1]=0;
       HWJALR++;
       if(r1==0){
         Runpc=0;
       }
       else{
       Runpc=registers[r1-1];
       }

     }
     if (e=="RET")
     {
       Runpc=retr1;
       HWJALR++;
     }

   }
   else {
     inscycles[indx]--;// if the ins in excuction queue and not run yet, decrement it
     qEx.enqueue(indx);
     qEx.enqueue(inst);
   }
 }
 while (!qEx.isEmpty()){
   var index=qEx.dequeue();
   var inst=qEx.dequeue();
   executable.enqueue(index);
   executable.enqueue(inst);
 }
  t++;
}
Runpc = 0;
registers=[0,0,0,0,0,0,0];
var n = 0;
while(instarray[Runpc/4]!=""){
  if (n > 99)
 {
  return;
 }
indx=Runpc/4;//updated index
var e = instarray[indx];
var par1=par1array[indx];
var par2=par2array[indx];
var par3=par3array[indx];
var cyc=inscycles[indx];
if (e=="LW"){
  var r=par1;
  var imm=par2;
  var r2=par3;
  var loc;
  if(r2==0){
  loc=imm;
  }
  else {
    loc=registers[r2-1]+imm;
  }
  registers[r-1]=mem[loc];
Runpc+=4;
}
else if (e=="SW"){

  var r=par1;
  var imm=par2;
  var r2=par3;
  var loc
  if(r2==0){
  loc=imm;
  }
  else {
    loc=registers[r2-1]+imm;
  }
  mem[loc]=  registers[r-1];
  Runpc+=4;
}
else if (e=="ADD"){
  var r1=par1;
  var r2=par2;
  var r3=par3;
  if(r2==0)
  {registers[r1-1]=registers[r3-1];}
  else   if(r3==0)
    {registers[r1-1]=registers[r2-1];}
    else{
      registers[r1-1]=registers[r2-1]+registers[r3-1];
    }
  Runpc+=4;
  }
  else  if (e=="ADDI"){
      var r1=par1;
      var r2=par2;
      var imm=par3;
      if(r2==0)
      {registers[r1-1]=imm;}

        else{
          registers[r1-1]=registers[r2-1]+imm;
        }
        Runpc+=4;
}
else if (e=="NEG"){
  var r1=par1;
  var r2=par2;

  if(r2==0)
  {
    registers[r1-1] = 0;
  }
  else{
  registers[r1-1]=-1*registers[r2-1];
}
Runpc+=4;
}
else if (e=="MULL"){
  var r1=par1;
  var r2=par2;
  var r3=par3;
  if(r2==0||r3==0)
  {registers[r1-1]=0;}
  else{
  var num = registers[r2-1] * registers[r3-1];
  registers[r1-1]= Math.floor(num%(Math.pow(2,16)));
}
Runpc+=4;
}
else if (e=="MULH"){
  var r1=par1;
  var r2=par2;
  var r3=par3;
  if(r2==0||r3==0)
  {
    registers[r1-1]=0;
  }
  else{
  var num = registers[r2-1] * registers[r3-1];
  registers[r1-1]= Math.floor(num/(Math.pow(2,16)));
}
Runpc+=4;
}
else if(e=="BEQ"){
  var r1=par1;
  var r2=par2;
  var imm=par3;
  if (r1==0|r2==0){
    if(r1==0)
    {
      if(r2==0||registers[r2-1]==0)
      {Runpc=Runpc+imm;}
    }
    else if(r2==0)
    {
      if(r1==0||registers[r1-1]==0)
      {Runpc=Runpc+imm;}
    }
  }
  else if(registers[r1-1]==registers[r2-1]){
    Runpc=Runpc+imm;
  }
    else{
      Runpc+=4;
    }
  indx=Runpc/4;
}
else if(e=="JALR"){
  var r1=par1;
  retr1=Runpc+4;//separate register, when jalr is called , it save the address of pc in ra
  if(r1==0){
    Runpc=0;
  }
  else{
  Runpc=registers[r1-1];
  }
  indx=Runpc/4;
}
else if (e=="RET")
{
  Runpc=retr1;
  indx=Runpc/4;
}
n++;
}
document.getElementById('regtable').innerHTML ='<table style="float: right;font-size:20px;color:white;border-collapse: collapse;border-spacing: 5;width: 25%;border:2px;"><tr><td>reg 1</td><td>'+registers[0]+'</td></tr><tr><td>reg 2</td><td>'+registers[1]+'</td></tr><tr><td>reg 3</td><td>'+registers[2]+'</td></tr><tr><td>reg 4</td><td>'+registers[3]+'</td></tr><tr><td>reg 5</td><td>'+registers[4]+'</td></tr><tr><td>reg 6</td><td>'+registers[5]+'</td></tr><tr><td>reg 7</td><td>'+registers[6]+'</td></tr><table>';
var ind=0;
ftable='<table style="float: right;margin-right:200;border:2px;font-size:20px;color:white;border-collapse: collapse;border-spacing: 3;width: 25%;"> <tr><td rowspan="2"> PC </td> <td rowspan="2"> INSTRUCTION </td> <td rowspan="2"> ISSUED </td> <td colspan="2">EXECUTION</td><td rowspan="2"> WRITTEN </td></tr><tr><td>START</td><td>END</td></tr>';

while(instarray[ind]!=""){
 ftable+='<tr><td>';
 ftable+=ind;
 ftable+='</td><td>';
 ftable+=instarray[ind];
 ftable+='</td><td>';
 ftable+=issued[ind];
 ftable+='</td>';
 if(instarray[ind] == "ADDI"||instarray[ind] == "ADD"||instarray[ind] == "NEG")
 {
   ftable +='<td >';
   ftable +=(ended[ind]-1);
//<table style ="border:2px;font-size:20px;color:white;border-collapse: collapse;border-spacing: 3;width: 25%;"><tr><td style="text-align:center">';
 }
 else if(instarray[ind] == "BEQ"||instarray[ind] == "JALR"||instarray[ind] == "RET")
 {
   ftable +='<td >';
   ftable +=(ended[ind]);
 //<table style ="border:2px;font-size:20px;color:white;border-collapse: collapse;border-spacing: 3;width: 25%;"><tr><td style="text-align:center">';
 }
 else if(instarray[ind] == "SW"||instarray[ind] == "LW")
 {
   ftable +='<td >';
   ftable +=(ended[ind]-2);
//<table style ="border:2px;font-size:20px;color:white;border-collapse: collapse;border-spacing: 3;width: 25%;"><tr><td style="text-align:center">';
 }
 else if(instarray[ind] == "MULL"||instarray[ind] == "MULH")
 {
   ftable +='<td >';
   ftable +=(ended[ind]-5);
//<table style ="border:2px;font-size:20px;color:white;border-collapse: collapse;border-spacing: 3;width: 25%;"><tr><td style="text-align:center">';
 }
 ftable+='</td><td>';//'<td style="text-align:center">';
 ftable+=ended[ind];
 ftable+='</td><td>';//'</tr></table></td><td>';
 ftable+=written[ind];
 ftable+='</td></tr>';
ind++;
}
 ftable+='</table>';
 if(branch == 0 && t ==0)
 {
   document.getElementById('stattable').innerHTML='<table style="float: right;font-size:20px;color:white;border-collapse: collapse;border-spacing: 5;width: 25%;border:2px;"><tr><td>Total Number of Cycles</td><td>'+t+'</td></tr><tr><td>Branches Encountered</td><td>'+branch+'</td></tr><tr><td>Branch Mispredictions</td><td>'+(branch - branch_correct_predictions)+'</td></tr><tr><td>Misprediction Percentage</td><td>'+'0'+'%</td></tr><tr><td>IPC</td><td>'+'0'+'</td></tr></table>';

 } else if (branch == 0 && t != 0)
 {
   document.getElementById('stattable').innerHTML='<table style="float: right;font-size:20px;color:white;border-collapse: collapse;border-spacing: 5;width: 25%;border:2px;"><tr><td>Total Number of Cycles</td><td>'+t+'</td></tr><tr><td>Branches Encountered</td><td>'+branch+'</td></tr><tr><td>Branch Mispredictions</td><td>'+(branch - branch_correct_predictions)+'</td></tr><tr><td>Misprediction Percentage</td><td>'+'0'+'%</td></tr><tr><td>IPC</td><td>'+ h1/t +'</td></tr></table>';

 } else {
   document.getElementById('stattable').innerHTML='<table style="float: right;font-size:20px;color:white;border-collapse: collapse;border-spacing: 5;width: 25%;border:2px;"><tr><td>Total Number of Cycles</td><td>'+t+'</td></tr><tr><td>Branches Encountered</td><td>'+branch+'</td></tr><tr><td>Branch Mispredictions</td><td>'+(branch - branch_correct_predictions)+'</td></tr><tr><td>Misprediction Percentage</td><td>'+(((branch - branch_correct_predictions)/(branch))*100)+'%</td></tr><tr><td>IPC</td><td>'+ h1/t +'</td></tr></table>';

 }
document.getElementById('cyctable').innerHTML =ftable;
}





function addins(){

  document.getElementById('rst').removeAttribute('hidden');
	var e = document.getElementById("inst");
	var par1=document.getElementById("p1").value;
	var par2=document.getElementById("p2").value;
	var par3=document.getElementById("p3").value;
  if (e.value=="BEQ"){
      var r1=parseInt(par1);
      var r2=parseInt(par2);
      var imm=parseInt(par3);
      if (imm%4 !=0)
    {
      window.alert("BRANCH INPUT MUST BE DIVSIBLE BY 4 AND GREATER THAN 0");
      return;
    }
    pc+=4;
    h1++;////each time number updated and incremented by 1
  	var indx=pc/4;
  		instarray[indx]=e.value;
  		par1array[indx]=r1;
  		par2array[indx]=r2;
  		par3array[indx]=imm;
      inscycles[indx]=1;
      stins+='<tr><td><PC>';
      stins+=pc;
      stins+='</PC></td><td><jump>';

      stins+=e.value;
      stins+= "</jump><num> ";
      stins+= r1;
      stins+=", ";
      stins+= r2;
      stins+= ",";
      stins+=imm;
      stins+='<br>';
        stins+='</num></td></tr>'
    }
    else if (e.value=="NEG"){
      pc+=4;
      h1++;////each time number updated and incremented by 1
        var indx=Runpc/4;
       var r1=parseInt(par1);
       var r2=parseInt(par2);
         instarray[indx]=e.value;
       par1array[indx]=r1;
       par2array[indx]=r2;
       par3array[indx]=0;
       inscycles[indx]=2;

     stins+='<tr><td><PC>';
     stins+=pc;
     stins+='</PC></td><td><mul>';
   stins+=e.value;
   stins+= "</mul> \t<num>";
   stins+= r1;
   stins+=", ";
   stins+= r2;
   stins+='<br>';
     stins+='</num></td></tr>'

     }

  else if (e.value=="MULL"){
    pc+=4;
    h1++;////each time number updated and incremented by 1
    	var indx=pc/4;
    var r1=parseInt(par1);
    var r2=parseInt(par2);
    var r3=parseInt(par3);
    instarray[indx]="MULL";
    par1array[indx]=r1;
    par2array[indx]=r2;
    par3array[indx]=r3;
    inscycles[indx]=6;

    stins+='<tr><td><PC>';
    stins+=pc;
    stins+='</PC></td><td><mul>';
  stins+=e.value;
  stins+= "</mul> \t<num>";
  stins+= r1;
  stins+=", ";
  stins+= r2;
  stins+= ",";
  stins+=r3;
  stins+='<br>';
    stins+='</num></td></tr>'
  }
  else if (e.value=="MULH"){
    pc+=4;
    h1++;////each time number updated and incremented by 1
    var indx=pc/4;
    var r1=parseInt(par1);
    var r2=parseInt(par2);
    var r3=parseInt(par3);
    instarray[indx]=e.value;
    par1array[indx]=r1;
    par2array[indx]=r2;
    par3array[indx]=r3;
    inscycles[indx]=6;

    stins+='<tr><td><PC>';
    stins+=pc;
    stins+='</PC></td><td><mul>';
  stins+=e.value;
  stins+= "</mul> \t <num>";
  stins+= r1;
  stins+=", ";
  stins+= r2;
  stins+= ",";
  stins+=r3;
  stins+='<br>';
    stins+='</num></td></tr>'
  }

	else if (e.value=="LW"){
    pc+=4;
    h1++;////each time number updated and incremented by 1
		var r=parseInt(par1);
		var imm=parseInt(par2);
		var r2=parseInt(par3);
		var loc;
		var indx=pc/4;
		instarray[indx]=e.value;
		par1array[indx]=r;
		par2array[indx]=imm;
		par3array[indx]=r2;
    inscycles[indx]=3;

    stins+='<tr><td><PC>';
    stins+=pc;
    stins+='</td></PC><td><INST>';
    stins+=e.value;
    stins+= "</INST><num> ";
    stins+= r;
    stins+=", ";
    stins+= imm;
    stins+= "(";
    stins+=r2;
    stins+=')<br>';
    stins+='</num></td></tr>';
	}
  else if (e.value=="SW"){
    pc+=4;
    h1++;////each time number updated and incremented by 1
    var r=parseInt(par1);
    var imm=parseInt(par2);
    var r2=parseInt(par3);
    var loc;
	var indx=pc/4;
		instarray[indx]=e.value;
		par1array[indx]=r;
		par2array[indx]=imm;
		par3array[indx]=r2;
    inscycles[indx]=3;
    mem[loc]=  registers[r-1];
    stins+='<tr><td><PC>';
    stins+=pc;
    stins+='</td></PC><td><INST>';

    stins+=e.value;
    stins+= "</INST><num> \t";
    stins+= r;
    stins+=", ";
    stins+= imm;
    stins+= "(";
    stins+=r2;
    stins+=')<br>';
      stins+='</num></td></tr>'
  }

  else if(e.value=="JALR"){
    var r1=parseInt(par1);
    if (registers[r1-1]%4 !=0)
  {
    window.alert("JALR INPUT MUST BE DIVSIBLE BY 4");
    return;
  }
    pc+=4;
    h1++;////each time number updated and incremented by 1
    var indx=pc/4;
  		instarray[indx]=e.value;
  		par1array[indx]=r1;
      inscycles[indx]=1;
      stins+='<tr><td><PC>';
      stins+=pc;
      stins+='</PC></td><td><jump>';

      stins+=e.value;
      stins+= "</jump> \t <num>";
      stins+= r1;
      stins+='<br>';
        stins+='</num></td></tr>'
  }
  else if(e.value=="RET"){
    pc+=4;
    h1++;////each time number updated and incremented by 1
    var indx=pc/4;
    instarray[indx]=e.value;
    inscycles[indx]=1;
    stins+='<tr><td><PC>';
    stins+=pc;
    stins+='</PC></td><td><jump>';

    stins+=e.value;
    stins+='<br>';
      stins+='</jump></td></tr>'
  }
  else if (e.value=="ADD"){
    pc+=4;
    h1++;////each time number updated and incremented by 1
    var r1=parseInt(par1);
    var r2=parseInt(par2);
    var r3=parseInt(par3);
	var indx=pc/4;
		instarray[indx]=e.value;
		par1array[indx]=r1;
		par2array[indx]=r2;
		par3array[indx]=r3;
    inscycles[indx]=2;

    stins+='<tr><td><PC>';
    stins+=pc;
    stins+='</PC></td><td><inc>';
      stins+=e.value;
      stins+= "</inc> \t <num>";
      stins+= r1;
      stins+=", ";
      stins+= r2;
      stins+= ",";
      stins+=r3;
      stins+='<br>';
        stins+='</num></td></tr>'
    }
    else  if (e.value=="ADDI"){
      pc+=4;
      h1++;////each time number updated and incremented by 1
        var r1=parseInt(par1);
        var r2=parseInt(par2);
        var imm=parseInt(par3);
		var indx=pc/4;
		instarray[indx]=e.value;
		par1array[indx]=r1;
		par2array[indx]=r2;
		par3array[indx]=imm;
    inscycles[indx]=2;
    stins+='<tr><td><PC>';
    stins+=pc;
    stins+='</PC></td><td><inc>';
          stins+=e.value;
          stins+= "</inc> \t <num>";
          stins+= r1;
          stins+=", ";
          stins+= r2;
          stins+= ",";
          stins+=imm;
          stins+='<br>';
            stins+='</num></td></tr>'
  }

	document.getElementById('insdone').innerHTML ='<MSG> you made '+h1+' instructions<br></MSG>'+stins;

  document.getElementById('submit3').removeAttribute('disabled');
}

function updatemem(){
  document.getElementById('submit2').removeAttribute('disabled');
	var loc=document.getElementById("loc");
	var mvalue=document.getElementById("val");
	var loc2=loc.value;
	var location=parseInt(loc2);
	var val2=mvalue.value;
	var value2=parseInt(val2);
	mem[location]=value2;
  document.getElementById('loc').value = " ";
  document.getElementById('val').value = " ";
}
function updatex(){
var e = document.getElementById("inst");
if (e.value == "")
{
  document.getElementById('p1').setAttribute('disabled', true);
  document.getElementById('p2').setAttribute('disabled', true);
  document.getElementById('p3').setAttribute('disabled', true);
  document.getElementById('submit').setAttribute('disabled', true);
  document.getElementById('1st').innerHTML='<br>';
  document.getElementById('2nd').innerHTML='<br>';
  document.getElementById('3rd').innerHTML='<br>';
  document.getElementById('p1').value = " ";
  document.getElementById('p2').value = " ";
  document.getElementById('p3').value = " ";
}
if (e.value=="LW"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').removeAttribute('disabled');
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').setAttribute('required','required');
document.getElementById('1st').innerHTML="rd";
document.getElementById('p1').min = 1;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="imm";
document.getElementById('p2').min = -64;
document.getElementById('p2').max = 63;
document.getElementById('3rd').innerHTML="rs1";
document.getElementById('p3').min = 0;
document.getElementById('p3').max = 7;
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
  }
if (e.value=="SW"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').removeAttribute('disabled');
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').setAttribute('required','required');
document.getElementById('1st').innerHTML="rs2";
document.getElementById('p1').min = 0;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="imm";
document.getElementById('p2').min = -64;
document.getElementById('p2').max = 63;
document.getElementById('3rd').innerHTML="rs1";
document.getElementById('p3').min = 0;
document.getElementById('p3').max = 7;
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
  }
if (e.value=="BEQ"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').removeAttribute('disabled');
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').setAttribute('required','required');
document.getElementById('1st').innerHTML="rs1";
document.getElementById('p1').min = 0;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="rs2";
document.getElementById('p2').min = 0;
document.getElementById('p2').max = 7;
document.getElementById('3rd').innerHTML="imm";
document.getElementById('p3').removeAttribute('min');
document.getElementById('p3').removeAttribute('max');
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
  }
if (e.value=="JALR"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').setAttribute('disabled', true);
document.getElementById('p3').setAttribute('disabled', true);
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').removeAttribute('required');
document.getElementById('p3').removeAttribute('required');
document.getElementById('1st').innerHTML="rs1";
document.getElementById('p1').min = 0;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML='<br>';
document.getElementById('3rd').innerHTML='<br>';
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
  }
if (e.value=="RET"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').setAttribute('disabled', true);
document.getElementById('p2').setAttribute('disabled', true);
document.getElementById('p3').setAttribute('disabled', true);
document.getElementById('p1').removeAttribute('required');
document.getElementById('p2').removeAttribute('required');
document.getElementById('p3').removeAttribute('required');
document.getElementById('1st').innerHTML='<br>';
document.getElementById('2nd').innerHTML='<br>';
document.getElementById('3rd').innerHTML='<br>';
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
  }
if (e.value=="ADD"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').removeAttribute('disabled');
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').setAttribute('required','required');
document.getElementById('1st').innerHTML="rd";
document.getElementById('p1').min = 1;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="rs1";
document.getElementById('p2').min = 0;
document.getElementById('p2').max = 7;
document.getElementById('3rd').innerHTML="rs2";
document.getElementById('p3').min = 0;
document.getElementById('p3').max = 7;
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
  }
if (e.value=="NEG"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').setAttribute('disabled', true);
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').removeAttribute('required');
document.getElementById('1st').innerHTML="rd";
document.getElementById('p1').min = 1;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="rs1";
document.getElementById('p2').min = 0;
document.getElementById('p2').max = 7;
document.getElementById('3rd').innerHTML='<br>';
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
  }
if (e.value=="ADDI"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').removeAttribute('disabled');
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').setAttribute('required','required');
document.getElementById('1st').innerHTML="rd";
document.getElementById('p1').min = 1;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="rs1";
document.getElementById('p2').min = 0;
document.getElementById('p2').max = 7;
document.getElementById('3rd').innerHTML='imm';
document.getElementById('p3').removeAttribute('min');
document.getElementById('p3').removeAttribute('max');
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
    }
if (e.value=="MULL"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').removeAttribute('disabled');
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').setAttribute('required','required');
document.getElementById('1st').innerHTML="rd";
document.getElementById('p1').min = 1;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="rs1";
document.getElementById('p2').min = 0;
document.getElementById('p2').max = 7;
document.getElementById('3rd').innerHTML='rs2';
document.getElementById('p3').min = 0;
document.getElementById('p3').max = 7;
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
    }
if (e.value=="MULH"){
document.getElementById('submit').removeAttribute('disabled');
document.getElementById('p1').removeAttribute('disabled');
document.getElementById('p2').removeAttribute('disabled');
document.getElementById('p3').removeAttribute('disabled');
document.getElementById('p1').setAttribute('required', 'required');
document.getElementById('p2').setAttribute('required', 'required');
document.getElementById('p3').setAttribute('required','required');
document.getElementById('1st').innerHTML="rd";
document.getElementById('p1').min = 1;
document.getElementById('p1').max = 7;
document.getElementById('2nd').innerHTML="rs1";
document.getElementById('p2').min = 0;
document.getElementById('p2').max = 7;
document.getElementById('3rd').innerHTML='rs2';
document.getElementById('p3').min = 0;
document.getElementById('p3').max = 7;
document.getElementById('p1').value = " ";
document.getElementById('p2').value = " ";
document.getElementById('p3').value = " ";
    }
}
function updateunit(){
  var e = document.getElementById("changeunit");
  document.getElementById("unit").min = 1;
  if (e.value=="LW UNIT"){
HWLW = document.getElementById("unit").value;
  }
  else if (e.value=="SW UNIT"){
  HWSW = document.getElementById("unit").value;
    }
    else if (e.value=="BEQ UNIT"){
  HWBEQ= document.getElementById("unit").value;
    }
    else if (e.value=="JALR/RET UNIT"){
  HWJALR = document.getElementById("unit").value;
    }
    else if (e.value=="ADD/NEG/ADDI UNIT"){
  HWARTH = document.getElementById("unit").value;
    }
    else if (e.value=="MULL/MULH UNIT"){
  HWMUL = document.getElementById("unit").value;
    }
    document.getElementById('unit').value = " ";
}
</script>

    </header>
    <body>
      <i>
      <a href = "index.php">
      <img src = "339-3392172_go-back-to-tpd-home-page-icon-sellit.png"  height = 75 width = 75/>
    </a>
    <a href = "choice.php">
    <img src = "835558919617800347.png"  height = 75 width = 75/>
  </a>
  </i>
  <br>
      <center>
      <p> TOMASULO'S ALGORITHM </p>
      </center>
        <div id="regtable"></div>
  <div id = "stattable"></div>
	 <D> MEMORY LOAD </D>
	  <form action = "javascript:updatemem()" method = "POST">
        <table>
		<tr>
		<th> <E> location </E> </th>
		<th> <E> value </E> </th>
		</tr>
          <tr>
            <th>
			<input type = "number" name="loc" id="loc" required>
    </th>
			<th>
			<input type = "number" name="val" id="val" required>
    </th>
			</tr>
			</table>

			   <button type = "submit" id = "submit2" name = "shoot2"> Submit </button>
      </form>

      <D> Number of Reservation Stations </D>
       <form action = "javascript:updateunit();" method = "POST">
        <table>
          <tr>
            <th><br></th>
            <th><E>RESERVATION STATIONS: </E></div></th>
          </tr>
          <tr>
            <th>
      <select name="changeunit" id="changeunit" onchange="javascript:updateunit();">
        <option value="">Please Select</option>
        <option value="LW UNIT">LW UNIT</option>
        <option value="SW UNIT">SW UNIT</option>
        <option value="BEQ UNIT">BEQ UNIT</option>
        <option value="JALR/RET UNIT">JALR/RET UNIT</option>
        <option value="ADD/NEG/ADDI UNIT">ADD/NEG/ADDI UNIT</option>
        <option value="MULL/MULH UNIT">MULL/MULH UNIT</option>
      </select>
    </th>
    <th>
       <input type = "number" name="unit" id = "unit" required> </div>
     </th>
     </tr>
   </table>
       <button type = "submit" id = "changesub" name = "changesub"> Submit </button>
      </form>

      <D> INSTRUCTION </D>
       <form action = "javascript:addins();" method = "POST">
        <table>
          <tr>
            <th><br></th>
            <th><E><div id=1st></E></div></th>
            <th><E><div id=2nd></E></div></th>
            <th><E><div id=3rd></E></div></th>
          </tr>
          <tr>
            <th>
      <select name="inst" id="inst" onchange="javascript:updatex();">
        <option value="">Please select</option>
        <option value="LW">LW</option>
        <option value="SW">SW</option>
        <option value="BEQ">BEQ</option>
        <option value="JALR">JALR</option>
        <option value="RET">RET</option>
        <option value="ADD">ADD</option>
        <option value="NEG">NEG</option>
        <option value="ADDI">ADDI</option>
        <option value="MULL">MULL</option>
        <option value="MULH">MULH</option>
      </select>
    </th>
    <th>  <div id = "first">
       <input type = "number" name="p1" id = "p1" disabled = true> </div>
     </th>
     <th> <div id = "second">
       <input type = "number" name="p2" id = "p2" disabled = true> </div>
     </th>
     <th> <div id = "third">
       <input type = "number" name="p3" id = "p3" disabled = true> </div>
     </th>

     </tr>
   </table>
       <button type = "submit" id = "submit" name = "shoot" disabled> Submit </button>
      </form>
      <div id = "cyctable"></div>
<div id="insdone"></div>
      <form action = "javascript:run2()" method = "POST">
      <button type = "submit" id = "submit3" name = "shoot" >RUN CODE</button>
    </form>
    <input type="button" value="Reset Code" id = "rst" onClick="window.location.reload(true)" hidden>
<br>
    <br>
<br>
<?php

 ?>
</body>
</html>

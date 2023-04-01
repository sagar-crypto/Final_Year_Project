const express = require("express")
const bodyParser = require("body-parser")
const request = require("request")
const mysql = require('mysql');
const crypto = require("crypto");
const { getEnvironmentData } = require("worker_threads");

const app = express();
app.use(express.static("public"))
app.use(bodyParser.urlencoded({extended:true}))
app.engine('html', require('ejs').renderFile);

const db = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : '',
    database : 'kerberos'
});
const db2 = mysql.createConnection({
        host     : 'localhost',
        user     : 'root',
        password : '',
        database : 'project'
});

db.connect((err) => {
    if(err){
        console.log(err);;
    }
    console.log('MySql Connected...');
});
db2.connect((err) => {
    if(err){
        console.log(err);;
    }
    console.log('MySql Connected...');
});

app.get("/", function(req,res){
    let arr = []
    for (let i = 0; i < Object.keys(req.query).length; i++){
        arr.push([Object.keys(req.query)[i], Object.values(req.query)[i]]);
    }
    res.render(__dirname+"/index.html", {phpFormData: arr})
})
app.post("/",function(req ,res){
    var TGT = req.body.Ticket
    var Session = req.body.Session
    var username = req.body.username
    var domain = req.body.domain || "Edmingle";
    //console.log(TGT)
    //console.log(Session)
    //console.log(username)
    //console.log(domain)
    iv=undefined
    tgt=undefined
    key=undefined


    function decrypt(iv,tgt,key){
        //decrypt here
        //console.log("decrypt called")
        let decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
        let TGT_decrypted = decipher.update(TGT, 'hex', 'utf8');
        TGT_decrypted+=decipher.final('utf8')
        //console.log(TGT_decrypted)
        if(TGT_decrypted==tgt){
            res.redirect('http://localhost/Saarthi-main/saarthi/')
        }
        else{
            res.sendFile(__dirname+"/index2.html")
        }
    }

    function getEncVars(callback,id,domainID){
        //console.log("getencvars called");
        let q1= "SELECT * FROM `user_auth_tickets` WHERE uid =?"
        let q2= "SELECT * FROM `krbtgt` WHERE domain_id = ?"
        db.query(q1,[id], (err,result) => {
            iv= result[0].iv
            tgt= result[0].tgt_raw
            //console.log("q1");
            //console.log(result[0])
        });
        db.query(q2,[domainID], (err,result) => {
            if(err){
                throw err;
            }
            //console.log("q2");
            //console.log(result)
            key= result[0].password
        });
        var intvl = setInterval(function() {
            if (iv && tgt && key) {
                clearInterval(intvl);
                callback(iv,tgt,key);
            }
        },100);
    }
    function getData(callback){
        //console.log("getdata called");
        let sql = `SELECT * FROM users WHERE username = ? and domain = ?`;
        let query = db.query(sql,[username,domain], (err, result) => {
            //console.log(result);
            id=result[0].id;
            domainID=result[0].domain_id;
        });
        var intvl = setInterval(function() {
            if (id && domainID) {
                clearInterval(intvl);
                callback(decrypt,id,domainID);
            }
        },1000);
    }
    getData(getEncVars);

    let q3 = sql = "INSERT INTO `crops`(`name`, `cname`, `latitude`, `longitude`, `location`, `price`)VALUES (?, ?, 82.8628, 135.0000, ?, ?)";
    
    let bind_arr= req.body.phpFormData.split(',').filter((item, i) => {
        if (i % 2 != 0){
            return item
        }
    });

    bind_arr = bind_arr.map(item => {
        let ref = "";
        try {
            ref = Number(item);
        }
        catch (e){
            ref = item
        }
        if(ref){
            item = ref
        }
        return item;
    })


    db2.query(q3, bind_arr, (err, result) => {
        if(result){
            res.redirect('http://localhost/Saarthi-main/saarthi/');
        }
    })


})

app.listen(4000 , function(){
    console.log("Server running on port 4000")
})
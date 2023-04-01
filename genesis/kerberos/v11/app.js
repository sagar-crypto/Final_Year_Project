const express = require("express")
const bodyParser = require("body-parser")
const request = require("request")
const mysql = require('mysql');
const crypto = require("crypto");
const cookieParser = require("cookie-parser");
const LocalStorage = require('node-localstorage').LocalStorage;

const app = express();
app.use(express.static("public"))
app.use(bodyParser.urlencoded({extended:true}))
app.engine('html', require('ejs').renderFile);
app.use(cookieParser());

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
    res.render(__dirname+"/index.html")
})
app.post("/", function(req,res){
    console.log(req.body);
    const username = req.body.username
    const domain = req.body.net
    const realm = req.body.service
    const gpa = req.body.img;
    const pass = req.body.gpaVal;
    res.cookie( 'domain' , domain);

    iv = crypto.randomBytes(16)
    TGT = crypto.randomBytes(24).toString("hex")
    console.log(TGT)

    var TGTsave = TGT
    data=''
    TGT_encrypted=undefined
    session_key_encrypted=undefined
    //get domain specific krbtgt password//
    function encrypt(key){
        /*
        console.log('2'); 
        console.log(key);
        */
        var intervl = setInterval(function() {
            if (key) { 
                console.log('3'); 
                console.log(key);
                let cipher = crypto.createCipheriv('aes-256-cbc',key , iv)
                TGT_encrypted = cipher.update(TGT,'utf-8','hex')
                TGT_encrypted+=cipher.final('hex')
                //console.log(TGT_encrypted)
                clearInterval(intervl);
            }
        },100);
    }
    function getTGT(domain,callback){
        console.log('0'); 
        console.log(data);
        let sqld= 'select * from krbtgt where domain_name =?';
        db.query(sqld,[domain], (err, result) => {
            data = Object.values(JSON.parse(JSON.stringify(result)));
            /*
            console.log('edwfaweffwearffererfwwawearffefdarfffawearerfwerfaweafeeffewwearfe');
            console.log(data[0].password);
            */
        });
        var intvl = setInterval(function() {
            if (data) {
                clearInterval(intvl);
                
                //console.log('1'); 
                //console.log(data);
                
               //console.log(data[0].domain_id)
                res.cookie('domainID',data[0].domain_id)
                callback(data[0].password);
            }
        },100);
    }

    function getSession(pass){
        var x= 0
        var pass1=""
        var len = pass.length
    
        while(x < 32){
            x+=len
            if(x>32){
                break;
            }
            pass1+=pass.toString()
        }
        if(pass1.length<32){
            var y = 32 -pass1.length
            for(var i = 0 ; i<y ; i++){
                pass1+=pass[i].toString()
            }
        }
        /*session key encrypt*/
        let session_key = crypto.randomBytes(24).toString("hex")
        let s_key = pass1
    
        let s_cipher = crypto.createCipheriv('aes-256-cbc', s_key , iv)
        session_key_encrypted = s_cipher.update(session_key,'utf-8','hex')
        session_key_encrypted+=s_cipher.final('hex')
        //console.log("session")
        //console.log(session_key_encrypted)
    }
    function saveTicket(rows,tgt,sk){
        console.log(rows);
        let saveQuery= "INSERT INTO `user_auth_tickets` (`uid`, `tgt`, `sessionKey`, `iv`, `tgt_raw`) VALUES (?,?,?,?,?)";
        db.query(saveQuery,[rows[0].id,tgt,sk,iv,TGTsave]);
    }
    existence=undefined
    existingTGT=undefined
    existingSK=undefined
    function checkExistence(id){
        console.log("id",id);
        let q= "SELECT sessionKey ,tgt FROM `user_auth_tickets` WHERE uid =?";
            db.query(q,[id],function(err,resolution){
                const row1 = Object.values(JSON.parse(JSON.stringify(resolution)));
                if(resolution.length== 0){
                    existence= 0;
                    console.log("nullll") 
                }
                else{
                    console.log("not null")
                    console.log(row1)
                    existingTGT=row1[0].tgt
                    console.log(existingTGT)
                    existingSK=row1[0].sessionKey
                    console.log(existingSK)
                    //console.log( "This is an existing sk from database",existingSK)
                    existence= 1;
                }
        
            });
    }

    let sql = `SELECT * FROM users WHERE username = ? and domain = ?`;
    let query = db.query(sql,[username,domain], (err, result) => {
        const rows = Object.values(JSON.parse(JSON.stringify(result)));
        if(pass==rows[0].password){
            console.log("seeeeeeeee thisss",checkExistence(rows[0].id));
            var intvl = setInterval(function() {
                if (existence==0 || existence==1) {
                    if(existence==1){
                        console.log("under this is the exsiting secret key and exsiting TGT")
                        console.log(existingSK)
                        console.log(existingTGT)
                        var int = setInterval(function() {
                            if (existingSK && existingTGT) {
                                console.log('This is if we have another user ');
                                clearInterval(int);
                                res.cookie( 'userID',rows[0].id);
                                res.cookie('domainID',rows[0].domain_id);
                                console.log(existingSK)
                                console.log(existingTGT)
                                res.render(__dirname+"/success.html",{pass:existingTGT,pass1:existingSK})
                            }
                        },100);
                        
                    }else if(existence==0){
                        getTGT(domain,encrypt);
                        getSession(pass);
                        console.log(TGT_encrypted);
                        var int = setInterval(function() {
                            if (TGT_encrypted) {
                                console.log('4'); 
                                console.log(TGT_encrypted);
                                clearInterval(int);
                                res.cookie( 'userID',rows[0].id);
                                res.render(__dirname+"/success.html",{pass:TGT_encrypted,pass1:session_key_encrypted})
                                saveTicket(rows,TGT_encrypted,session_key_encrypted);
                            }
                        },100);
                    }
                    clearInterval(intvl);
                    console.log("existence called",existence)
                }
            },100);
        }
        else{
            
            res.sendFile(__dirname+"/index.html")
        }
    });

})

app.post("/about",function(req ,res){
    var TGT = req.body.Ticket
    var Session = req.body.Session
    console.log(TGT)
    console.log(Session)
    var userdata = req.cookies;
    console.log(userdata);
    iv=undefined
    tgt=undefined
    key=undefined

    function decrypt(iv,tgt,key){
        //decrypt here
        let decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
        let TGT_decrypted = decipher.update(TGT, 'hex', 'utf8');
        TGT_decrypted+=decipher.final('utf8')
        if(TGT_decrypted==tgt){
            res.redirect("https://peaceful-reaches-52168.herokuapp.com/ftp/")
        }
        else{
            res.sendFile(__dirname+"/index.html")
        }
    }

    function getEncVars(callback){
        
        console.log(userdata)
        id=userdata.userID
        domainID=userdata.domainID

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
            console.log("q2");
            //console.log(result)
            key= result[0].password
        });
        var intvl = setInterval(function() {
            if (iv && tgt && key) {
                clearInterval(intvl);
                callback(iv,tgt,key);
                console.log("decrypt called")
            }
        },100);
    }

    getEncVars(decrypt);

})

app.post("/redirect",function(req ,res){
    res.sendFile(__dirname + "/success2.html")
})

app.listen(3001 , function(){
    console.log("Server running on port 3001")
})
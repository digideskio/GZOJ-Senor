function Vector(a, b) {
    this.x = a;
    this.y = b
}
Vector.prototype = {
    rotate: function(b) {
        var a = this.x;
        var c = this.y;
        this.x = Math.cos(b) * a - Math.sin(b) * c;
        this.y = Math.sin(b) * a + Math.cos(b) * c;
        return this
    },
    mult: function(a) {
        this.x *= a;
        this.y *= a;
        return this
    },
    clone: function() {
        return new Vector(this.x, this.y)
    },
    length: function() {
        return Math.sqrt(this.x * this.x + this.y * this.y)
    },
    subtract: function(a) {
        this.x -= a.x;
        this.y -= a.y;
        return this
    },
    set: function(a, b) {
        this.x = a;
        this.y = b;
        return this
    }
};
function Petal(a, f, b, e, c, d) {
    this.stretchA = a;
    this.stretchB = f;
    this.startAngle = b;
    this.angle = e;
    this.bloom = d;
    this.growFactor = c;
    this.r = 1;
    this.isfinished = false
}
Petal.prototype = {
    draw: function() {
        var a = this.bloom.garden.ctx;
        var e, d, c, b;
        e = new Vector(0, this.r).rotate(Garden.degrad(this.startAngle));
        d = e.clone().rotate(Garden.degrad(this.angle));
        c = e.clone().mult(this.stretchA);
        b = d.clone().mult(this.stretchB);
        a.strokeStyle = this.bloom.c;
        a.beginPath();
        a.moveTo(e.x, e.y);
        a.bezierCurveTo(c.x, c.y, b.x, b.y, d.x, d.y);
        a.stroke()
    },
    render: function() {
        if (this.r <= this.bloom.r) {
            this.r += this.growFactor;
            this.draw()
        } else {
            this.isfinished = true
        }
    }
};
function Bloom(e, d, f, a, b) {
    this.p = e;
    this.r = d;
    this.c = f;
    this.pc = a;
    this.petals = [];
    this.garden = b;
    this.init();
    this.garden.addBloom(this)
}
Bloom.prototype = {
    draw: function() {
        var c, b = true;
        this.garden.ctx.save();
        this.garden.ctx.translate(this.p.x, this.p.y);
        for (var a = 0; a < this.petals.length; a++) {
            c = this.petals[a];
            c.render();
            b *= c.isfinished
        }
        this.garden.ctx.restore();
        if (b == true) {
            this.garden.removeBloom(this)
        }
    },
    init: function() {
        var c = 360 / this.pc;
        var b = Garden.randomInt(0, 90);
        for (var a = 0; a < this.pc; a++) {
            this.petals.push(new Petal(Garden.random(Garden.options.petalStretch.min, Garden.options.petalStretch.max), Garden.random(Garden.options.petalStretch.min, Garden.options.petalStretch.max), b + a * c, c, Garden.random(Garden.options.growFactor.min, Garden.options.growFactor.max), this))
        }
    }
};
function Garden(a, b) {
    this.blooms = [];
    this.element = b;
    this.ctx = a
}
Garden.prototype = {
    render: function() {
        for (var a = 0; a < this.blooms.length; a++) {
            this.blooms[a].draw()
        }
    },
    addBloom: function(a) {
        this.blooms.push(a)
    },
    removeBloom: function(a) {
        var d;
        for (var c = 0; c < this.blooms.length; c++) {
            d = this.blooms[c];
            if (d === a) {
                this.blooms.splice(c, 1);
                return this
            }
        }
    },
    createRandomBloom: function(a, b) {
        this.createBloom(a, b, Garden.randomInt(Garden.options.bloomRadius.min, Garden.options.bloomRadius.max), Garden.randomrgba(Garden.options.color.rmin, Garden.options.color.rmax, Garden.options.color.gmin, Garden.options.color.gmax, Garden.options.color.bmin, Garden.options.color.bmax, Garden.options.color.opacity), Garden.randomInt(Garden.options.petalCount.min, Garden.options.petalCount.max))
    },
    createBloom: function(a, f, d, e, b) {
        new Bloom(new Vector(a, f), d, e, b, this)
    },
    clear: function() {
        this.blooms = [];
        this.ctx.clearRect(0, 0, this.element.width, this.element.height)
    }
};
Garden.options = {
    petalCount: {
        min: 8,
        max: 15
    },
    petalStretch: {
        min: 0.1,
        max: 3
    },
    growFactor: {
        min: 0.1,
        max: 1
    },
    bloomRadius: {
        min: 8,
        max: 10
    },
    density: 10,
    growSpeed: 1000 / 60,
    color: {
        rmin: 128,
        rmax: 255,
        gmin: 0,
        gmax: 128,
        bmin: 0,
        bmax: 128,
        opacity: 0.1
    },
    tanAngle: 60
};
Garden.random = function(b, a) {
    return Math.random() * (a - b) + b
};
Garden.randomInt = function(b, a) {
    return Math.floor(Math.random() * (a - b + 1)) + b
};
Garden.circle = 2 * Math.PI;
Garden.degrad = function(a) {
    return Garden.circle / 360 * a
};
Garden.raddeg = function(a) {
    return a / Garden.circle * 360
};
Garden.rgba = function(f, e, c, d) {
    return "rgba(" + f + "," + e + "," + c + "," + d + ")"
};
Garden.randomrgba = function(i, n, h, m, l, d, k) {
    var c = Math.round(Garden.random(i, n));
    var f = Math.round(Garden.random(h, m));
    var j = Math.round(Garden.random(l, d));
    var e = 5;
    if (Math.abs(c - f) <= e && Math.abs(f - j) <= e && Math.abs(j - c) <= e) {
        return Garden.rgba(i, n, h, m, l, d, k)
    } else {
        return Garden.rgba(c, f, j, k)
    }
};

var fstt;
$(function(){
	rf_func.push(festival_today);
	fstt=festival_today;
	if(/qixi_nobot/.test(document.cookie)) return;
	festival_today();
	function festival_today(){
$("#festival-eg").html('<div id="loveHeart" style="position:fixed;width:800px;height:600px;top:50%;left:50%;margin:-300px 0 0 -400px;z-index:100000;background-color:rgba(255,204,204,0.2);box-shadow: 0 0 10px rgba(0,0,0,0.3);"><a id="closelove" href="javascript:" onclick="document.cookie=\'qixi_nobot=1\';$(\'#loveHeart\').fadeOut(2000)" style="position:absolute;right:10px;top:20px">Close Heart & Do not bother</a><canvas id="garden"></canvas><div id="qixitxt" style="display:;margin-left:-150px;margin-top:-120px;position:fixed;left:50%;top:50%;font-family:楷体;font-size:18px">祝大家七夕快乐。愿天下有情人终成眷属。<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - 森。</div><div id="lovetxt" style="display:none;height:30px;width:270px;margin-left:-150px;margin-top:-40px;position:fixed;left:50%;top:50%;font-family:楷体;font-size:18px;text-align:">大胆追求属于自己的爱。</div><div id="lovewarn" style="display:none;height:30px;width:300px;margin-left:-150px;margin-top:0px;position:fixed;left:50%;top:50%;font-family:楷体;font-size:18px;text-align:">注：以上文字并不代表赞同中学生早恋。<br>中学生应当以学业为重，杜绝早恋。</div></div></div>');
offsetX = $("#loveHeart").width() / 2;offsetY = $("#loveHeart").height() / 2 - 55;$window = $(window);clientWidth = $(window).width();clientHeight = $(window).height();
showMessages();
			setTimeout(function () {
				startHeartAnimation();
				$("#loveHeart").animate({"background-color":"rgba(255,255,255,0.8)"},4000,"linear");
			}, 10);

    $loveHeart = $("#loveHeart");
    var a = $loveHeart.width() / 2;
    var b = $loveHeart.height() / 2 - 55;
    $garden = $("#garden");
    gardenCanvas = $garden[0];
    gardenCanvas.width = $("#loveHeart").width();
    gardenCanvas.height = $("#loveHeart").height();
    gardenCtx = gardenCanvas.getContext("2d");
    gardenCtx.globalCompositeOperation = "lighter";
    garden = new Garden(gardenCtx, gardenCanvas);
    $("#content").css("width", $loveHeart.width() + $("#code").width());
    $("#content").css("height", Math.max($loveHeart.height(), $("#code").height()));
    $("#content").css("margin-top", Math.max(($window.height() - $("#content").height()) / 2, 10));
    $("#content").css("margin-left", Math.max(($window.width() - $("#content").width()) / 2, 10));
    setInterval(function() {
        garden.render()
    },
    Garden.options.growSpeed);
}
});
var offsetX, offsetY, $window, clientWidth, clientHeight;
var gardenCtx,
gardenCanvas,
$garden,
garden;


function getHeartPoint(c) {
    var b = c / Math.PI;
    var a = 19.5 * (16 * Math.pow(Math.sin(b), 3));
    var d = -20 * (13 * Math.cos(b) - 5 * Math.cos(2 * b) - 2 * Math.cos(3 * b) - Math.cos(4 * b));
    return new Array(offsetX + a, offsetY + d)
}
function startHeartAnimation(callback) {
    var c = 50;
    var d = 10;
    var b = new Array();
    var a = setInterval(function() {
        var h = getHeartPoint(d);
        var e = true;
        for (var f = 0; f < b.length; f++) {
            var g = b[f];
            var j = Math.sqrt(Math.pow(g[0] - h[0], 2) + Math.pow(g[1] - h[1], 2));
            if (j < Garden.options.bloomRadius.max * 1.3) {
                e = false;
                break
            }
        }
        if (e) {
            b.push(h);
            garden.createRandomBloom(h[0], h[1])
        }
        if (d >= 30) {
            clearInterval(a);
			if(callback) callback();
            //showMessages();
        } else {
            d += 0.2
        }
    },
    c)
}
function showMessages()
{
	$("#qixitxt").typewriter(function(){$("#lovetxt").fadeIn(3000,function(){$("#lovewarn").fadeIn(2000);})});
}

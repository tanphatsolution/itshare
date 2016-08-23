function drawPieChart(dom, data) {
    dom = $(dom);
    if (!dom.length) {
        return;
    }
    var tagName = [];
    var tagNum = [];
    var i = 0;
    $.each(data, function (key, value) {
        tagName[i] = "%%.% - " + key;
        tagNum[i] = parseInt(value);
        i++;
    });

    var id = dom.attr('id');
    var text = dom.attr('data-label');
    var r = Raphael(id);
    r.text(100, 20, text).attr({ font: "20px sans-serif" });
    var pie = r.piechart(100, 130, 80,
        tagNum,
        { legend: tagName,
            legendpos: "east"
        });

    pie.hover(function () {
        this.sector.stop();
        this.sector.scale(1.1, 1.1, this.cx, this.cy);

        if (this.label) {
            this.label[0].stop();
            this.label[0].attr({ r: 7.5 });
            this.label[1].attr({ "font-weight": 800 });
        }
    }, function () {
        this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");
        if (this.label) {
            this.label[0].animate({ r: 5 }, 500, "bounce");
            this.label[1].attr({ "font-weight": 400 });
        }
    });
}

function getRandomColors() {
    var byndColors = ["#776B19","#1d1d1d","#e81c6e","#7c7c7c","#00aff2","#aaaaaa","#611bc9"];
    var randColors = ["#829813","#364f8a","#60cb94","#cf263b","#2471bb","#7fc398","#d2c66a",
                    "#2109dc","#66ad29","#9a9754","#640cdf","#257683","#d51e05","#4bb36e",
                    "#e7408a","#1ef173","#1756bc","#cff215","#15c2fb","#f010ab","#844a0",
                    "#c34021","#3e4cf2","#a28f5c","#a9d528","#7b1e43","#a5401c"];
    return byndColors.concat(randColors);
}

function drawBarChart(dom, data) {
    dom = $(dom);
    if (!dom.length) {
        return;
    }
    dom.height(250);
    var id = dom.attr('id');
    var text = dom.attr('data-label');
    var unit = dom.attr('data-unit');
    var units = dom.attr('data-units');
    var w = dom.width();
    var h = dom.height();
    var r = Raphael(id, w, h),
        fin = function () {
            var unitOption = unit;
            if (this.bar.value > 1) {
                unitOption = units;
            }
            this.flag = r.popup(this.bar.x, this.bar.y, this.bar.value + unitOption || '0').insertBefore(this);
        },
        fout = function () {
            this.flag.animate({opacity: 0}, 300, function () {this.remove();});
        };
    // Set title of bar chart
    r.text(300, 20, text).attr({ font: '20px sans-serif' });
    // Setting preserveAspectRatio to 'none' -> Stretch the SVG
    r.setViewBox(0, 0, w, h);
    r.canvas.setAttribute('preserveAspectRatio', 'none');
    var options = {
        legend: ['10,20,30'],
        stacked: false,
        type: 'square',
        colors: getRandomColors()
    };
    var labels = data.labels;
    var values = data.values;
    var cnt = [];
    for (var i = 0; i < labels.length; i++) {
        cnt.push(labels[i].match(/\s+/g) == null ? 0 : labels[i].match(/\s+/g).length);
    }
    var barChart = r.barchart(10, 20 - Math.max.apply(Math,cnt)*8, w, 220, values, options);
    barChart.hover(fin, fout);
    // Draw x-axis
    barChart.addlabels = function () {
        for (var i = 0; i < this.bars.length; i++) {
            x = this.bars[i].x;
            y = this.bars[i].y + this.bars[i].h + 10 + cnt[i]*8;
            r.text(x, y, labels[i].replace(/\s+/g,'\n')).attr({ font: '14px sans-serif' });
            var center = this.bars[i].y + this.bars[i].h/2;
            r.text(x, center, values[i]).attr({
                font: '14px sans-serif',
                fill: '#fff'
            });
        }
    };
    barChart.addlabels();
    // Barchart responsive
    window.onresize = function(event) {
        var w = dom.width();
        var h = 250;
        r.setSize(w,h);
    }
}
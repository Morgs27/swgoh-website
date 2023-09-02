
async function refresh_profile(username,allycode){

    console.log("Refreshing " + username);

    modal = document.querySelector(".refresh_modal");
    modal.classList.add("active");
    button = document.querySelector(".export");
    button.classList.add("spin");

    var content = document.querySelector(".profile_content_container");
    content.classList.add("blur");

    text = document.querySelector(".load_link_text");

    $.ajax({
        url: "includes/refresh_user_data.inc.php",
        method: "POST",    
        data: {username: username, ally_code: allycode},
        success: function(data){
            console.log(data);
            window.location.href = "profile.php?" + username;
            success_refresh(); 
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
            fail_refresh();
        }
    });

}

function success_refresh(){
    modal.classList.remove("active");
    content = document.querySelector(".profile_content_container");
    content.classList.add("blur");
    button.classList.remove("spin");
    button.classList.add("complete");
    button.querySelector(".export_open_text").innerHTML = "Refresh Successful"
    button.getElementsByTagName('i')[0].classList = "fa-solid fa-check";
    setTimeout(() => {
        button.classList.add("minimised");
    },5000)
}

function fail_refresh(){
    content = document.querySelector(".profile_content_container");
    content.classList.add("blur");

    modal.classList.remove("active");
    button.classList.remove("spin");
    button.classList.add("complete");
    button.classList.add("fail");
    button.querySelector(".export_open_text").innerHTML = "Refresh Unsuccessful" 
    button.getElementsByTagName('i')[0].classList = "fa-solid fa-xmark";
    setTimeout(() => {
        button.classList.remove("complete");
        button.classList.remove("fail");
        button.querySelector(".export_open_text").innerHTML = "Refresh Data" 
        button.getElementsByTagName('i')[0].classList = "fa-solid fa-arrow-rotate-right";
    },5000)
}

function change_info(id){
    if (id == "progress"){
        load_charts()
    }
    selector_bar = document.querySelector(".profile_selector_bar");
    active = selector_bar.querySelector(".active");
    active.classList.remove("active");

    active_content = document.getElementById("c_" + active.id)
    active_content.classList.remove("active");

    selector = document.getElementById(id);
    selector.classList.add("active");

    selector_content = document.getElementById("c_" + id);
    selector_content.classList.add("active");
}

function toggle_content(id){
    arrow = document.getElementById(id);
    arrow.classList.toggle("active");
    var content = arrow.parentElement.nextElementSibling;
    content.classList.toggle("active");
}

function load_charts(){
    $.ajax({
        url: "includes/get_chart_data.inc.php",
        method: "POST",   
        data: {username:window.username},
        success: function(info){
            info = JSON.parse(info);
            console.log(info);
            var dates = info[0];
            var gps = info[1];
            var char_gps = info[3];
            var ship_gps = info[2];
           
            // GPS
            load_chart(gps,dates,"GP","rgba(255, 255, 255, 0.2)","gp")

            // Char GPS
            load_chart(char_gps,dates,"GP","rgba(255, 255, 255, 0.2)","char_gp")

            // Ship GPS
            load_chart(ship_gps,dates,"GP","rgba(255, 255, 255, 0.2)","ship_gp")

            load_chart_coloured(info[4],dates,"Rating","rgba(255, 255, 255, 0.2)","skill_rating")

            load_chart(info[5],dates,"Rank","rgba(255, 255, 255, 0.2)","arena_rank")

            load_chart(info[6],dates,"Rank","rgba(255, 255, 255, 0.2)","ship_arena_rank")

            load_chart(info[7],dates,"Rank","rgba(255, 255, 255, 0.2)","omicrons")

            load_chart(info[8],dates,"Rank","rgba(255, 255, 255, 0.2)","zetas")

            console.log(info[9])
            load_chart(info[9],dates,"Rank","rgba(255, 255, 255, 0.2)","reliced")

   
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });


   
}

function load_chart(source,dates,label,color,id){
    data = []
    for (let i = 0; i < dates.length; i ++){
        data[i] = {x: dates[i], y: source[i]}
    }   
    ctx = document.getElementById(id).getContext('2d');
    var config = {
        type: 'line',
        data: {
            datasets: [
            {
                label: label,
                data: data,
                fill: false,
                borderColor: color,
                borderWidth: 2,
                pointBackgroundColor: 'transparent',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 3,
                pointHoverBorderColor: 'rgba(255, 255, 255, 0.2)',
                pointHoverBorderWidth: 10,
                lineTension: 0,
            }
            ]
        },
        options: {
            layout: {
                padding: 5
            },
            responsive: true,
            elements: { 
                point: {
                    radius: 6,
                    hitRadius: 6, 
                    hoverRadius: 6 
                } 
            },
            legend: {
                display: false,
            },
            tooltips: {
                backgroundColor: 'RGB(41, 45, 45)',
                displayColors: false,
                bodyFontSize: 14,
                boxPadding: 5,
                titleAlign:'right',
                callbacks: {
                label: function(tooltipItems, data) { 
                    return label + ': ' + tooltipItems.yLabel.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace(".0","");
                },
                title: function(tooltipItems, data) { 
                    return "";
                }
                }
            },
            scales: {
                xAxes: [{
                type:'time',
                distribution: 'series',
                time: {
                    displayFormats: {
                        quarter: 'DD MM'
                    }
                }
                }],
                yAxes: [{
                    ticks: {
                        callback: function(label, index, labels) {
                            return label.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace(".0","");
                        }
                    }
                }]
            }
        },
    };
            
  
    var chart = new Chart(ctx,config);
}


function load_chart_coloured(source,dates,label,color,id){
    data = []
    for (let i = 0; i < dates.length; i ++){
        data[i] = {x: dates[i], y: source[i]}
    }   
    ctx = document.getElementById(id).getContext('2d');
    var config = {
        type: 'line',
        data: {
            datasets: [
            {
                label: label,
                data: data,
                fill: false,
                borderColor: color,
                borderWidth: 2,
                pointBackgroundColor: 'transparent',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 3,
                pointHoverBorderColor: 'rgba(255, 255, 255, 0.2)',
                pointHoverBorderWidth: 10,
                lineTension: 0,
            }
            ]
        },
        options: {
            layout: {
                padding: 5
            },
            responsive: true,
            elements: { 
                point: {
                    radius: 6,
                    hitRadius: 6, 
                    hoverRadius: 6 
                } 
            },
            legend: {
                display: false,
            },
            tooltips: {
                backgroundColor: 'RGB(41, 45, 45)',
                displayColors: false,
                bodyFontSize: 14,
                boxPadding: 5,
                titleAlign:'right',
                callbacks: {
                label: function(tooltipItems, data) { 
                    return label + ': ' + tooltipItems.yLabel.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace(".0","");
                },
                title: function(tooltipItems, data) { 
                    return "";
                }
                }
            },
            scales: {
                xAxes: [{
                type:'time',
                distribution: 'series',
                time: {
                    displayFormats: {
                        quarter: 'DD MM'
                    }
                }
                }],
                yAxes: [{
                    ticks: {
                        callback: function(label, index, labels) {
                            return label.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,').replace(".0","");
                        }
                    },
                    // gridLines: {
                    //     color: {
                    //         callback: function (context){
                    //             return "red";
                    //         }
                    //     }
                        
                    // }
                }]
            }
        },
    };
            
  
    var chart = new Chart(ctx,config);
}



function pie_chart(id,value1,value2,label1,label2,img1,img2,color1,color2){


    new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: {
        labels: [label1,label2],
          datasets: [
            {
              backgroundColor: [color1, color2],
              data: [value1,value2],
              borderColor: 'RGB(41, 45, 45)',
              borderWidth: 4,
              hoverBackgroundColor: 'white',
            }
          ]
        },
        spacing: 10,
        options: {
        tooltips: {
            backgroundColor: 'RGB(41, 45, 45)',
            borderColor: 'rgba(255,255,255,0.7)',
            displayColors: false,
            bodyFontSize: 14,
            boxPadding: 5,
            callbacks: {
                label: function(tooltipItems, data) { 
                    index = tooltipItems.index;
                    label = data.labels[index];
                    return label;
                }
            }
        },
          aspectRatio: 1,
          responsive: false,
          title: {
              display: false,
          },
          legend:{
              display: false,
          },
        //   tooltips: {
        //       enabled: false,
        //   },
          layout: {
              padding: 0,
          },
          plugins: {
            labels: [
                {
                    render: 'image',
                    position: 'border',
                    images: [
                        {src: img1,width: 19,height: 19},
                        {src: img2,width: 17,height: 19}
                    ]
                }
            ],
          }
        }
    });
}


function pie_chart_no_image(id,value1,value2,label1,label2,img1,img2,color1,color2){


    new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: {
        labels: [label1,label2],
          datasets: [
            {
              backgroundColor: [color1, color2],
              data: [value1,value2],
              borderColor: 'RGB(41, 45, 45)',
              borderWidth: 4,
              hoverBackgroundColor: 'white',
            }
          ]
        },
        spacing: 10,
        options: {
        tooltips: {
            backgroundColor: 'RGB(41, 45, 45)',
            borderColor: 'rgba(255,255,255,0.7)',
            displayColors: false,
            bodyFontSize: 14,
            boxPadding: 5,
            callbacks: {
                label: function(tooltipItems, data) { 
                    index = tooltipItems.index;
                    label = data.labels[index];
                    return label;
                }
            }
        },
          aspectRatio: 1,
          responsive: false,
          title: {
              display: false,
          },
          legend:{
              display: false,
          },
        //   tooltips: {
        //       enabled: false,
        //   },
          layout: {
              padding: 0,
          },
          plugins: {
            labels: [
                {
                    render: 'label',
                    fontColor: 'white'
                }
            ],
          }
        }
    });
}



function mod_chart(speeds){

    speeds = JSON.parse(speeds);

    data = [];
    labels = [];
    total = 0;
    number = 0;
    for (let i = 10; i< 29;i++){
        point = speeds[i];
        labels.push(i);
        data.push(point);
        total += (point * i);
        number += point;
    }

    var average = (total / number).toFixed(2);

    // document.querySelector(".average").innerHTML = "Average Speed: " + average;

    Chart.Tooltip.positioners.top = elements => {
        let model = elements[0]._model;
        return {
          x: model.x,
          y: model.y - 10,
        };
      };


    new Chart(document.getElementById("mod_graph"), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['RGB(62, 149, 205)','#ff264b','#B000BB','#00B84D','#cd763e','RGB(62, 149, 205)','#ff264b','#B000BB','#00B84D','#cd763e','RGB(62, 149, 205)','#ff264b','#B000BB','#00B84D','#cd763e','RGB(62, 149, 205)','#ff264b','#B000BB','#00B84D'],
            }]
        },
        options: {
            layout: {
                padding: {
                    top: 10
                }
            },
            responsive: true,
            tooltips: {
                // backgroundColor: 'transparent',
                // displayColors: false,
                // bodyFontSize: 14,
                // boxPadding: 5,
                // position: 'top',
                // xAlign:'center',
                // yAlign: null,
                // callbacks: {
                //     title: function(tooltipItems, data) { 
                //         return ""
                //     }
                // }
                enabled: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        display: false,
                    },
                    gridLines: {
                        display: true,
                        drawBorder: true,
                        zeroLineWidth: 0,
                        // lineWidth: 0,
                        drawTicks: false,
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0)',
                        // zeroLineColor: 'white',
                        // zeroLineWidth:2,
                        borderWidth: 0
                    }
                }],
                xAxes: [{
                    ticks: {
                        fontColor: 'rgba(255,255,255,0.8)',
                    },
                    gridLines: {
                        display: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Speed',
                        fontColor: 'rgba(255,255,255,0.8)',
                    },
                    categoryPercentage: 0.8,
                    
                }]
            },
            legend: {
                display: false
            },
            plugins: {
                labels: {
                    render: 'value',
                    // fontColor: 'transparent'
                },
                annotation: {
                    annotations: {
                      line1: {
                        type: 'line',
                        yMin: 60,
                        yMax: 60,
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 2,
                      }
                    }
                }
            }
        },
    });
}


function mod_type_chart(types){

    types = JSON.parse(types);
    types.shift();
    labels = ['Health','Offence','Defence','Speed',['Crit','Chance'],['Crit','Damage'],'Potency','Tenacity']

    new Chart(document.getElementById("mod_graph_types"), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: types,
                backgroundColor: ['RGB(62, 149, 205)','#ff264b','#B000BB','#00B84D','#cd763e','RGB(62, 149, 205)','#ff264b','#B000BB'],
            }]
        },
        options: {
            layout: {
                padding: {
                    top: 20
                }
            },
            responsive: true,
            tooltips: {
                // backgroundColor: 'transparent',
                // displayColors: false,
                // bodyFontSize: 14,
                // boxPadding: 5,
                // position: 'top',
                // xAlign:'center',
                // yAlign: null,
                // callbacks: {
                //     title: function(tooltipItems, data) { 
                //         return ""
                //     }
                // }
                enabled: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        display: false,
                    },
                    gridLines: {
                        display: true,
                        drawBorder: true,
                        zeroLineWidth: 2,
                        lineWidth: 0,
                        drawTicks: false,
                        drawOnChartArea: true,
                        color: 'rgba(0, 0, 0, 0)',
                        zeroLineColor: 'white',
                        zeroLineWidth:2,
                        borderWidth: 0
                    }
                }],
                xAxes: [{
                    ticks: {
                        fontColor: 'rgba(255,255,255,0.8)',
                    },
                    gridLines: {
                        display: false
                    },
                    categoryPercentage: 0.9,
                    
                }]
            },
            legend: {
                display: false
            },
            plugins: {
                labels: {
                    render: 'value',
                    // fontColor: 'transparent'
                }
            }
        },
    });
}
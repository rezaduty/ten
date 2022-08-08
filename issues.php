<?php

include("session.php");


if (!empty($_SESSION['current_scan_report'])) {

if (file_exists($_SESSION['current_scan_report'])) {
  $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
} else {
  $_SESSION['current_scan_report'] = '';
}} else {
error_log("[ERROR] session: current_scan_report is null.");
}

$chart_plugin_metrics = Array();
$chart_severity_metrics = Array();
$chart_vulntype_metrics = Array();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/favicon.ico">
  
  <title>Raptor: Source Vulnerability Scanner</title>

  <!-- Bootstrap core CSS -->
  <link href="dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom styles for this template -->
  <link href="assets/css/dashboard.css" rel="stylesheet">
  
  <!-- CSS for highlight.js syntax highlighting library -->
  <link rel="stylesheet" href="dist/css/xcode.css">
  <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
  <!--[if lt IE 9]>
<script src="../../assets/js/ie8-responsive-file-warning.js">
</script>
<![endif]-->
  <script src="assets/js/ie-emulation-modes-warning.js">
  </script>
  
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js">
</script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js">
</script>
<![endif]-->
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <script src="dist/js/jquery-1.11.1.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Modal Dialog JS/CSS -->
    <script src="dist/js/bootstrap-dialog.min.js"></script>
    <link href="dist/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <style>
        .login-dialog .modal-dialog {
          width: 300px;
        }
    </style>
  </head>
  
  <body>
    
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">
              Toggle navigation
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
          </button>
          <a class="navbar-brand" href="#">Raptor: Source Code Scanner</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="/">
                Dashboard
              </a>
            </li>
            <li>
              <a href="/">
                Settings
              </a>
            </li>
            <li>
              <a href="/">
                Profile
              </a>
            </li>
            <li>
              <a href="logout.php">
                Logout
              </a>
            </li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>
    
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="scan.php">Scan</a></li>
            <li class="active"><a href="issues.php">Issues <span class="sr-only">(current)</span></a></li>
            <li><a href="analytics.php">Analytics</a></li>
            <li><a href="history.php">History</a></li>
            <li><a href="editrules.php">Rules Editor</a></li>
          </ul>
          <ul class="nav nav-sidebar">
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">
            Issue Summary
              </h1>
              <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="text-decoration: none">
                        Issues Statistics
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div class="row placeholders">
                        <!--<div class="col-sm-12 placeholder">-->
                          <div id="chart_vulntype" style="height: 400px;">
                            loading...
                          </div>
                        <!--</div>-->
                        <div class="col-sm-6 placeholder">
                          <div id="chart_lang" style="width: 100%; height: 400px;">
                            loading...
                          </div>
                        </div>
                        <div class="col-sm-6 placeholder">
                          <div id="chart_severity" style="width: 100%; height: 400px;">
                            loading...
                          </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <table style="width: 100%">
                  <tbody>
                  <tr><td>
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="text-decoration: none">
                    Issues List
                  </a>
                </td>
                <td>
                  <button type="button" class="btn btn-success export" style="margin: 0px; float: right; text-decoration: none"> Export </button>
                </td>
              </tr>
            </tbody>
            </table>
                </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse">
                <h2 id="lblScanSource" class="sub-header">
                  <?php 
                    if (!empty($_SESSION['current_scan_report'])) {
                      echo $data['scan_info']['app_path'];
                    }
                  ?>
                </h2>
                <h4 id="lblIssueCount">
                  <?php
                    if (!empty($_SESSION['current_scan_report'])) {
                      echo 'Total Warnings: ' . $data['scan_info']['security_warnings'];
                    }
                  ?>
                </h4>
    <div class="table-responsive">
      <table class="table table-striped" id="issues_table">
        <thead><tr>
        <th>Rule ID</th>
        <th>Type</th>
        <th>File</th>
        <th>Description</th>
        <th>Snippet</th>
        <th>Plugin</th>
        <th>Severity</th>
        <th>Reference</th>
        <th>Location</th>
        <th>User Input</th>
        <th>Render Path</th>
        </tr></thead>
        
        <tfoot><tr>
        <th>Rule ID</th>
        <th>Type</th>
        <th>File</th>
        <th>Description</th>
        <th>Snippet</th>
        <th>Plugin</th>
        <th>Severity</th>
        <th>Reference</th>
        <th>Location</th>
        <th>User Input</th>
        <th>Render Path</th>
        </tr></tfoot>
        <tbody>
  <?php
    if (!empty($_SESSION['current_scan_report'])) {
      
      if ($_SESSION['git_type'] === 'internal' || $data['scan_info']['repo_type'] === 'internal') {
          $git_url = $_SESSION['git_endpoint']['internal'];
        } elseif ($_SESSION['git_type'] === 'external' || $data['scan_info']['repo_type'] === 'external') {
          $git_url = $_SESSION['git_endpoint']['external'];
        }

      for($i=0; $i < count($data['warnings']); $i++) {
        @$rule_id = !empty($data['warnings'][$i]['warning_code']) ? $data['warnings'][$i]['warning_code'] : '-';

      if (array_key_exists($data['warnings'][$i]['plugin'], $chart_plugin_metrics)) {
        $chart_plugin_metrics[$data['warnings'][$i]['plugin']] += 1;
      } else {
          $chart_plugin_metrics[$data['warnings'][$i]['plugin']] = 0;
      }

      if (array_key_exists($data['warnings'][$i]['severity'], $chart_severity_metrics)) {
        $chart_severity_metrics[$data['warnings'][$i]['severity']] += 1;
      } else {
          $chart_severity_metrics[$data['warnings'][$i]['severity']] = 0;
      }

      if (array_key_exists($data['warnings'][$i]['warning_type'], $chart_vulntype_metrics)) {
        $chart_vulntype_metrics[$data['warnings'][$i]['warning_type']] += 1;
      } else {
          $chart_vulntype_metrics[$data['warnings'][$i]['warning_type']] = 0;
      }

      if ( strstr(ltrim($data['warnings'][$i]['file']), '.zip') ) {
          $line_content = '<td><a target="_blank" href="#">' . ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'] . '</a></td>';
      } else {
         $line_content = '<td><a target="_blank" href="' . $git_url . $data['scan_info']['app_path'] . 
           '/blob/master/' . ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'] . '">' . 
           ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'] . '</a></td>';
      }
      
      if (!function_exists('highlight_type')) {
        function highlight_type($plugin) {
        $type = '';
        switch ($plugin) {
          case 'android':
            $type = 'java';
          case 'php':
            $type = 'php';
          case 'actionscript':
            $type = 'actionscript';
          case 'fsb_android':
            $type = 'java';
          case 'fsb_injection':
            $type = 'java';
          case 'fsb_crypto':
            $type = 'java';
          case 'fsb_endpoint':
            $type = 'java';
        }
        return $type;
      }}
      
      echo '<tr>' . 
           '<td>' . $rule_id . '</td>' .
           '<td>' . $data['warnings'][$i]['warning_type'] . '</td>' . 
           $line_content .
           '<td>' . $data['warnings'][$i]['message'] . '</td>' .
           '<td><pre><code style="white-space:nowrap" class="'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</pre></code></td>' .
           '<td>' . $data['warnings'][$i]['plugin'] . '</td>' .
           '<td>' . $data['warnings'][$i]['severity'] . '</td>' .
           '<td><a target="_blank" href="' . $data['warnings'][$i]['link'] . '">'. $data['warnings'][$i]['link'] .'</a></td>'; 

      if (gettype(@$data['warnings'][$i]['location']) === 'array') {
        echo '<td>';
        for($loc=0; $loc < count($data['warnings'][$i]['location']); $loc++) {
        
          echo '<a target="_blank" href="' . $git_url . $data['scan_info']['app_path'] . 
          '/blob/master/' . $data['warnings'][$i]['file'] . '#L' . $data['warnings'][$i]['location'][$loc] . '">' . 
          $data['warnings'][$i]['location'][$loc] . '</a>, ';
        
        }

        echo '</td>';
      } else {
          echo '<td>' . @$data['warnings'][$i]['location'] . '</td>';    
      }

      if (gettype(@$data['warnings'][$i]['user_input']) === 'array') {
        $usrinput = '';

        foreach($data['warnings'][$i]['user_input'] as $value) {
          $usrinput .= '<a target="_blank" href="' . $git_url . $data['scan_info']['app_path'] . 
          '/blob/master/' . ltrim($data['warnings'][$i]['file'], '/') . '#L' . $value . '">' . $value . '</a>, ';
        }
        echo '<td>' . substr($usrinput, 0, strlen($usrinput)-1) . '</td>';
        } else {
          echo '<td>' . @$data['warnings'][$i]['user_input'] . '</td>';    
        }

    echo '<td>' . @$data['warnings'][$i]['render_path'] . '</td>' .'</tr>';
    }
      $_SESSION['scan_active'] = false;
      $_SESSION['git_repo'] = '';
      $_SESSION['zip_name'] = '';
      $_SESSION['upload_id'] = '';
  } else {
      for($i=0; $i < 11; $i++) {
        echo '<td></td>';
    }}
?>
              </tbody>
            </table>
      </div>
          </div>
            </div>
              </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      
      google.load("visualization", "1", {
        packages:["corechart"]
      });
      
      //Plugin Metrics
      google.setOnLoadCallback(drawPluginChart);
      function drawPluginChart() {
        var data = google.visualization.arrayToDataTable([
          ['Plugin Type', 'Issue Count'], 
          <?php
            $lang_metrics = "";
            foreach ($chart_plugin_metrics as $key => $value) {
              $lang_metrics .= "['" . $key . "', " . $value . "],";
            }
            echo $lang_metrics;
          ?>
        ]);
      
        var options = {
          'title': 'Plugin-Type Metrics',
          'pieHole': 0.5,
          'width':500,
          'height':300
        };
        
        var chart = new google.visualization.PieChart(document.getElementById('chart_lang'));
        chart.draw(data, options);
      }
      
      //Severity Metrics
      google.setOnLoadCallback(drawSeverityChart);
      function drawSeverityChart() {
        var data = google.visualization.arrayToDataTable([
          ['Severity Type', 'Count'], 
          <?php
            $sev_metrics = "";
            foreach ($chart_severity_metrics as $key => $value) {
              $sev_metrics .= "['" . $key . "', " . $value . "],";
            }
            echo $sev_metrics;
          ?>
        ]);

        var options = {
          'title': 'Severity Metrics',
          'pieHole': 0.5,
          'width':500,
          'height':300
        };
        
        var chart = new google.visualization.PieChart(document.getElementById('chart_severity'));
        chart.draw(data, options);
      }
      
      //Issue Type Metrics
      google.setOnLoadCallback(drawVulntypeChart);
      function drawVulntypeChart() {
        var data = google.visualization.arrayToDataTable([
          ['Vulnerability Type', 'Count'], 
          <?php
            $vuln_metrics = "";
            foreach ($chart_vulntype_metrics as $key => $value) {
              $vuln_metrics .= "['" . $key . "', " . $value . "],";
            }
            echo $vuln_metrics;
          ?>
        ]);
        
        var options = {
          'title': 'Issue Type Metrics',
          'pieHole': 0.5,
          'width':700,
          'height':400
        };
        
        var chart = new google.visualization.PieChart(document.getElementById('chart_vulntype'));
        chart.draw(data, options);
      }
      
    </script>
    
    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function () {
        //console.log($('issues_table'))
        $('#issues_table tfoot th').each( function () {
          var title = $('#issues_table thead th').eq($(this).index()).text();
          $(this).html('<input type="text"  class="txtSearchFilter" placeholder="Search ' + title + '" />');
        });

        var table = $('#issues_table').DataTable();
        
        $(".export").on("click",function(e) {
          var report_export_dialog_config = {
            type: 'type-primary',
            title: 'Export Report',
            message: 'How do you want to export the report?',
            buttons: [
            {
              icon: '',
              label: 'Current View',
              cssClass: 'btn-primary',
              autospin: true,
              autodestroy: true,
              action: function(dialogRef) {
                exportTableToCSV.apply($(this),[$("#issues_table"), "source-scan-report-view.csv"]);
                dialogRef.close();
              }
            }, {
              icon: '',
              label: 'Everything',
              cssClass: 'btn-warning',
              autospin: true,
              autodestroy: true,
              action: function(dialogRef) {
                table.columns().eq(0).each(function(colIdx) {
                    table.column(colIdx).search('').draw();
                });
                var prev_len = table.page.len();
                table.page.len(10000).draw();
                exportTableToCSV.apply($(this),[$("#issues_table"), "source-scan-report-all.csv"]);
                table.page.len(prev_len).draw();
                dialogRef.close();
              }
            }, {
              label: 'Close',
              cssClass: 'btn-danger',
              action: function(dialogRef) {
                dialogRef.close();
              }
            }]
          };

          BootstrapDialog.show(report_export_dialog_config);
        });
        
        // Apply the search
        table.columns().eq(0).each(function(colIdx) {
          $('input', table.column(colIdx).footer()).on('keyup change', function () {
            table.column(colIdx).search(this.value).draw();
          });
        });
      })
        
        function exportTableToCSV($table, filename) {
        var csv = [];
        var table_id = '#' + $table.attr('id') + ' tr';
        rows = $(table_id);
        var output = "";
        for(var i =0;i < rows.length;i++) {
          cells = $(rows[i]).find('td,th');
          csv_row = [];
          for (var j=0;j<cells.length;j++) {
            txt = cells[j].innerText;
            txt = '"' + txt.replace('n/a','') + '"';
            txt = txt.replace(/\n$/, "");
            csv_row.push(txt);
          }
            csv.push(csv_row.join(","));
            if (csv_row.join(",") !== '"","","","","","","","","","",""') {
              output += csv_row.join(",") + "\r\n";
            }
        }
        var csvContent = output;
        var pom = document.createElement('a');
        var blob = new Blob([csvContent], {
          type: 'text/csv;charset=utf-8;'
        });
        var url = URL.createObjectURL(blob);
        pom.href = url;
        pom.setAttribute('download', filename);
        pom.click();
      }
    </script>
    <script src="dist/js/bootstrap.min.js">
    </script>
    <script src="assets/js/docs.min.js">
    </script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js">
    </script>
    <link href="http://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script>
      var delete_timeout_id = setTimeout(function () {
        var master_search = document.getElementById('issues_table_filter');
        if (master_search.parentNode.removeChild(master_search)) {
          clearTimeout(delete_timeout_id)
        }
      }, 10);
    </script>
    
    
    <div id="global-zeroclipboard-html-bridge" class="global-zeroclipboard-container" style="position: absolute; left: 0px; top: -9999px; width: 15px; height: 15px; z-index: 999999999;">
      
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" width="100%" height="100%">
        
        <param name="movie" value="assets/flash/ZeroClipboard.swf?noCache=1417503608910">
        
        <param name="allowScriptAccess" value="never">
        
        <param name="scale" value="exactfit">
        
        <param name="loop" value="false">
        
        <param name="menu" value="false">
        
        <param name="quality" value="best">
        
        <param name="bgcolor" value="#ffffff">
        
        <param name="wmode" value="transparent">
        
        <param name="flashvars" value="">
        
        <embed src="assets/flash/ZeroClipboard.swf?noCache=1417503608910" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="100%" height="100%" name="global-zeroclipboard-flash-bridge" allowscriptaccess="never" allowfullscreen="false" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="" scale="exactfit">
        
      </object>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200" preserveAspectRatio="none" style="visibility: hidden; position: absolute; top: -100%; left: -100%;">
      <defs>
      </defs>
      <text x="0" y="10" style="font-weight:bold;font-size:10pt;font-family:Arial, Helvetica, Open Sans, sans-serif;dominant-baseline:middle">
        200x200
      </text>
    </svg>
    <script src="dist/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="dist/js/heartbeat.js"></script>
    <script>
    function deleteFilterView() {
      var filterFlag = false;
      
      $('.txtSearchFilter').each(function() {
        if($(this).val() != '') {
          filterFlag = true;
        }
      });

      if (!filterFlag) {
        var response = confirm('Do you really want to delete all the issues?');
        if(!response) {
          return 0;
        }
      }

      var issuesTable =$('#issues_table').dataTable();
      //get all nodes of the datatable it returns all rows
      var allNodes = issuesTable.fnGetNodes();
      //get filtered rows of datatable            
      var filteredRows = issuesTable._('tr', {"filter":"applied"});
      var filteredArray= [];
  
      for(var i=0; i<filteredRows.length; i++) {
        var item = filteredRows[i];
        //compare the github linenumber column 
        var column= item[2];
        filteredArray.push(column);
      }
  
      for(var i=0; i < allNodes.length; i++) {
        var rowData = allNodes[i];
        var columnData= $(rowData).find('td:eq(2)').html();
        //if the node is in filtered list remove it
        if(jQuery.inArray(columnData, filteredArray) > -1 ) {
          //here i am removing the row
          var rowData = issuesTable.fnDeleteRow(rowData, null, true);
        }
      }
      
      $('.txtSearchFilter').each(function() {
        $(this).val('');
      });
      
      var table = $('#issues_table').DataTable();
      table.search( '' ).columns().search( '' ).draw();

      var newIssuesCount = $('#issues_table_info').text().split('of')[1].trim().split(' ')[0];
      $("#lblIssueCount").text('Total Warnings: ' + newIssuesCount);

    }

    $(document).ready(function () {
      var btnFilterDeleteHTML = '<button type="button" id="btnFilterDelete" class="glyphicon glyphicon-trash btn btn-info btnFilterView" style="margin-left: 10px; float: left; text-decoration: none" onClick="deleteFilterView()"> </button>';
      $(btnFilterDeleteHTML).insertAfter("#issues_table_info");
    });
    </script>
  </body>
</html>
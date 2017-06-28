<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>JS Bin</title>

  <style>
  
  /* 
    CSS RESET
*/

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
  
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

a{
  animation : none;
    animation-delay : 0;
    animation-direction : normal;
    animation-duration : 0;
    animation-fill-mode : none;
    animation-iteration-count : 1;
    animation-name : none;
    animation-play-state : running;
    animation-timing-function : ease;
    backface-visibility : visible;
    background : 0;
    background-attachment : scroll;
    background-clip : border-box;
    background-color : transparent;
    background-image : none;
    background-origin : padding-box;
    background-position : 0 0;
    background-repeat : repeat;
    background-size : auto auto;
    border : 0;
    border-style : none;
    border-width : medium;
    border-color : inherit;
    border-bottom : 0;
    border-bottom-color : inherit;
    border-bottom-left-radius : 0;
    border-bottom-right-radius : 0;
    border-bottom-style : none;
    border-bottom-width : medium;
    border-collapse : separate;
    border-image : none;
    border-left : 0;
    border-left-color : inherit;
    border-left-style : none;
    border-left-width : medium;
    border-radius : 0;
    border-right : 0;
    border-right-color : inherit;
    border-right-style : none;
    border-right-width : medium;
    border-spacing : 0;
    border-top : 0;
    border-top-color : inherit;
    border-top-left-radius : 0;
    border-top-right-radius : 0;
    border-top-style : none;
    border-top-width : medium;
    bottom : auto;
    box-shadow : none;
    box-sizing : content-box;
    caption-side : top;
    clear : none;
    clip : auto;
    color : inherit;
    columns : auto;
    column-count : auto;
    column-fill : balance;
    column-gap : normal;
    column-rule : medium none currentColor;
    column-rule-color : currentColor;
    column-rule-style : none;
    column-rule-width : none;
    column-span : 1;
    column-width : auto;
    content : normal;
    counter-increment : none;
    counter-reset : none;
    cursor : auto;
    direction : ltr;
    display : inline;
    empty-cells : show;
    float : none;
    font : normal;
    font-family : inherit;
    font-size : medium;
    font-style : normal;
    font-variant : normal;
    font-weight : normal;
    height : auto;
    hyphens : none;
    left : auto;
    letter-spacing : normal;
    line-height : normal;
    list-style : none;
    list-style-image : none;
    list-style-position : outside;
    list-style-type : disc;
    margin : 0;
    margin-bottom : 0;
    margin-left : 0;
    margin-right : 0;
    margin-top : 0;
    max-height : none;
    max-width : none;
    min-height : 0;
    min-width : 0;
    opacity : 1;
    orphans : 0;
    outline : 0;
    outline-color : invert;
    outline-style : none;
    outline-width : medium;
    overflow : visible;
    overflow-x : visible;
    overflow-y : visible;
    padding : 0;
    padding-bottom : 0;
    padding-left : 0;
    padding-right : 0;
    padding-top : 0;
    page-break-after : auto;
    page-break-before : auto;
    page-break-inside : auto;
    perspective : none;
    perspective-origin : 50% 50%;
    position : static;
    /* May need to alter quotes for different locales (e.g fr) */
    quotes : '\201C' '\201D' '\2018' '\2019';
    right : auto;
    tab-size : 8;
    table-layout : auto;
    text-align : inherit;
    text-align-last : auto;
    text-decoration : none;
    text-decoration-color : inherit;
    text-decoration-line : none;
    text-decoration-style : solid;
    text-indent : 0;
    text-shadow : none;
    text-transform : none;
    top : auto;
    transform : none;
    transform-style : flat;
    transition : none;
    transition-delay : 0s;
    transition-duration : 0s;
    transition-property : none;
    transition-timing-function : ease;
    unicode-bidi : normal;
    vertical-align : baseline;
    visibility : visible;
    white-space : normal;
    widows : 0;
    width : auto;
    word-spacing : normal;
    z-index : auto;
    /* basic modern patch */
    all: initial;
    all: unset;
}


/* 
    GENERAL

    DARK GREEN: #016526
    NEWS GRAY SEPARADOR: #ededed
    NEWS GRAY DATA : #727273
    FB GREEN: #009028 
    FB BLUE:  #003770
*/

/* ->AJEITAR FONTE */
@font-face {
    font-family: Raleway;
    src: url(https://fonts.google.com/specimen/Raleway);
}

.tableWrapper{
  width:640px;
  min-height: 500px;
  background-color:white;
  font-family:Verdana, Arial;
}

.buttom{
  border:solid 2px #727273;
  text-align: right;
  padding-top:5px;
  padding-bottom:5px;
  padding-left: 10px;
  padding-right: 10px;
  font-size: 7pt;
  font-weight: bold;
  color: #727273;
  background-color:white;
 }

/*
  HEADER
*/

.header{
  border: solid 0px green;
  width: 100%;
}

.header .top{
  
}

.header .bottom{
  height: 50px;
  width:100%;
  background-color: #009028;
  color:white;
  font-weight: bold;
}

.header .bottom td{
  vertical-align:middle;
  text-align: center;
}

.header .bottom .title{
  width: 70%;
}

.header .bottom .subscrition{
  width: 30%; 
  padding-bottom:4px;
}

.header .bottom .subscrition .buttom{
  border:solid 2px white;
  text-align: right;
  padding-top:5px;
  padding-bottom:5px;
  padding-left: 10px;
  padding-right: 10px;
  font-size: 7pt;
  font-weight: bold;
  color: #727273;
  background-color:white;
  color: 727273;
}

/*
  BODY
*/

.body{
  min-height: 800px;
  width: 580px;
  margin:auto;
}

/*
  NEWS 
*/

.news{
  background-color:white;
  width:100%;
  margin-top:40px;
}



.news td{
  border: solid 0px black; 
}

.news .middle .col01{
  width:225px;
  vertical-align:middle;
}

.news .middle .col01 img{
  width:100%;
}

.news .col02{
  width:40px;
}

.news .col03{
}

.news .bottom {
  height:40px;  
}

.news .bottom .col03{
  text-align: right;
  vertical-align:bottom;
  padding-bottom: 8px;

}

.news .bottom .col03 .buttom{
  /*diferencas do botao normal*/

}

.news .top .col03 .header{
  width:100%;
    height:40px;
}

.news .top .col03 .header td{
  padding-top:5px;
  color: #727273;
}

.news .top .col03 .header .entityName{
  font-size:8pt;
  font-weight: bold;
  text-align:left;
}

.news .top .col03 .header .entityName .symbol{
  color: black;
}

.news .top .col03 .header .data{
  font-size:9pt;
  text-align:right;
}

.news .separator{
  border-bottom: 1.5pt solid #ededed;
  height:40px
}

.news .middle .content .title{
  color: #009028;
  font-weight: bold;
  font-size: 14pt;
  line-height: 25px;
}

.news .middle .content .divisor{
  height: 40px;
}

.news .middle .content .divisor .line{
  vertical-align:middle;
  padding-bottom:19px;
  color: #009028;
  font-weight: bold;
  font-size: 14pt;
}

.news .middle .content .text{
  color: #727273;
  font-family: ArialMT;
  text-align: justify;
}


/*
  FOOTER
*/

.footer{
  border: solid 1px pink;
  width: 100%;
  min-height: 100px;
}

  </style>
</head>
<body>
  
  <table class="tableWrapper">
    <tr>
      <td>
        
        <!-- HEADER -->
        <table class="header">
          <tr class="top">
            <td colspan="2">
              <img src="https://image.ibb.co/khMbv5/template_rhuan_projeto.png" alt="FB News Logo" />
            </td>  
          </tr>  
          <tr class="bottom" >
            <td class="title">
              FB NEWS N. #01 - 15/11/2007 
            </td>  
            <td class="subscrition">
              <a class="buttom" href="#">ALTERAR INSCRIÇÃO</a>
            </td>
          </tr>
        </table>
        
      </td>  
    </tr>
    <tr>
      <td>
        
        <!-- BODY -->
        <table class="body">
          <tr>
            <td>
                <table class="news">
                    <tr class="top" >
                      <td class="col01"> 
                      </td>
                      <td class="col02"> 
                      </td>
                      <td class="col03"> 
                        <table class="header">
                          <tr>
                            <td class="entityName">
                              <span class="symbol">&#10095;</span> ITA
                            </td>  
                            <td class="data">
                              5 de Maio de 2017 | Via Facebook
                            </td>
                          </tr>  
                        </table>
                      </td>
                    </tr>
                    <tr class="middle" >
                      <td class="col01"> 
                        <img src="https://image.ibb.co/khMbv5/template_rhuan_projeto.png" alt="FB News Logo" />
                      </td>
                      <td class="col02">  
                      </td>
                      <td class="col03"> 
                        <table class="content">
                          <tr class="title">
                            <td>
                              Últimos dias para realizar  sua inscrição para a prova do ITA 2                           
                            </td> 
                          </tr> 
                          <tr class="divisor">
                            <td class="line">
                              ______
                            </td> 
                          </tr> 
                          <tr>
                            <td class="text">
                              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam congue libero erat, eget aliquam dolor fermentum at.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam congue libero erat, eget aliquam dolor fermentum at.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam congue libero erat, eget aliquam dolor fermentum at.Lorem ierat, eget aliquam dolor fermentum at.
                            </td> 
                          </tr>  
                        </table>  
                      </td>
                    </tr> 
                    <tr class="bottom" >
                      <td class="col01"> 
                      </td>
                      <td class="col02"> 
                      </td>
                      <td class="col03"> 
                        <span class="buttom"><a href="#">LEIA MAIS...</a></span>
                      </td>
                    </tr> 
                    <tr class="separator">
                      <td colspan="3">
                        
                      </td>
                    </tr> 
                </table>  
              
            </td>  
          </tr>  
        </table> 
        
      </td>  
    </tr>
    <tr>
      <td>
        
        <!-- FOOTER -->
        <table class="footer">
          <tr>
            <td>
                FOOTER
            </td>  
          </tr> 
        </table>
        
        
      </td>  
    </tr>
  </table>

</body>
</html>
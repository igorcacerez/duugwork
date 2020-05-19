import Session from "./main/Session.js"

// Dados importantes
var Dados = {
    "url": "http://localhost/git/",
    "urlApi": "http://localhost/git/"
}


// Mascaras
$(".maskCEP").mask("99999-999");
$(".maskValor").mask("#.##0,00", {reverse: true});
$(".maskCPF").mask("999.999.999-99");
$(".maskCNPJ").mask("99.999.999/9999-99");
$(".maskTel").mask("(99) 9999-9999");
$(".maskCel").mask("(99) 99999-9999");
$(".maskPeso").mask("#.00", {reverse: true});
$(".maskNumero").mask("#", {reverse: true});
$(".maskCartao").mask("# ###0", {reverse: true});


/**
 * Método responsável por realizar uma requisição,
 * retornando uma promesa.
 * -------------------------------------------------
 * @author igorcacerez
 * -------------------------------------------------
 * @param tipo
 * @param url
 * @param dados
 * @return {Promise<any>}
 */
function enviaApi(tipo, url, dados = null, token = null, alertTipy = "swal")
{
    return new Promise(function (resolve, reject) {

        // Variaveis
        var header = {};

        // Verifica se informou o token
        if(token != null)
        {
            header.Token = 'Bearer ' + token;
        }


        // Verifica se o tipo é PUT
        if(tipo === "PUT")
        {
            if(dados !== null && dados !== "" && dados !== undefined)
            {
                // Objeto de conversao
                var object = {};

                // Percorre os dados
                dados.forEach((value, key) => {
                    // Reflect.has in favor of: object.hasOwnProperty(key)
                    if(!Reflect.has(object, key)){
                        object[key] = value;
                        return;
                    }
                    if(!Array.isArray(object[key])){
                        object[key] = [object[key]];
                    }
                    object[key].push(value);
                });

                // Converte o objeto em JSON
                dados = JSON.stringify(object);
            }
        }

        // Realiza a requisição
        $.ajax({
            url: url,
            headers: header,
            type: tipo,
            dataType: "json",
            data: dados,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                if(data.tipo === true)
                {
                    // Retorna o resultado
                    resolve(data)
                }
                else
                {
                    // Verifica se foi erro 401
                    if(data.code === 401)
                    {
                        // Redireciona o usuario para a página de logout
                        location.href = Dados.url + "login";
                    }
                    else
                    {
                        if(alertTipy === "swal")
                        {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: data.mensagem
                            });
                        }
                        else
                        {
                            alertify.error(data.mensagem);
                        }

                        reject(data);

                    } // Erro o token acabou

                } // Ocorreu algum erro
            }
        });

    });

} // End >> Fun::enviaApi()




/**
* Método responsável por disparar um alerta de erro
* -------------------------------------------------
* @author igorcacerez
* -------------------------------------------------
* @param mensagem
*/
function error(mensagem)
{
    Swal.fire({
        type: 'error',
        title: 'Oops...',
        text: mensagem
    });
} // End >> Fun::error()




/**
 * Método responsável por disparar um alerta de sucesso
 * -------------------------------------------------
 * @author igorcacerez
 * -------------------------------------------------
 * @param mensagem
 */
function success(mensagem)
{
    Swal.fire({
        type: 'success',
        title: 'Sucesso!',
        text: mensagem
    });
} // End >> Fun::success()




/**
 * Método responsável por calcular quanto tempo se passou
 * de um determinado post.
 * -------------------------------------------------------
 * @param data
 * @return {Promise<any>}
 */
function calculaData(data)
{
    // Retorna a promesa
    return new Promise((resolve, reject) => {

        var dataAtual = new Date();
        var dataPost = new Date(data);
        var resultado = "";

        var diferenca = Math.abs(dataAtual - dataPost); //diferença em milésimos e positivo
        var minuto = 1000*60; // milésimos de segundo correspondente a um minuto

        var totalMinuto = Math.round(diferenca/minuto); // valor total de minutos passados

        // Varifica se passou mais de 60 minutos
        if(totalMinuto >= 60)
        {
            var horasTotal =  Math.round(totalMinuto/60);

            // Verifica se é mais de 24horas
            if(horasTotal >= 24)
            {
                var diasTotal =  Math.round(horasTotal/24);

                // Verifica se é maior de 30 dias
                if(diasTotal >= 30)
                {
                    var mesTotal = Math.round(diasTotal/30);

                    if(mesTotal >= 12)
                    {
                        var anos = Math.round(mesTotal/12);

                        resultado = anos + " anos";
                    }
                    else
                    {
                        resultado = mesTotal + " messes";
                    }
                }
                else
                {
                    resultado = diasTotal + " dias";
                }
            }
            else
            {
                resultado = horasTotal + " horas";
            }
        }
        else
        {
            resultado = totalMinuto + " minutos";
        }

        // Resolve o role
        resolve(resultado);
    });
} // End >> Fun::calculaData()




/**
 * Método responsável por formatar um determinado numero
 * no pádrão de dinheiro.
 * -----------------------------------------------------
 * @param number
 * @param places
 * @param symbol
 * @param thousand
 * @param decimal
 * @return {string}
 */
function formatMoney(number, places, symbol, thousand, decimal)
{
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : "$";
    thousand = thousand || ",";
    decimal = decimal || ".";

    var negative = number < 0 ? "-" : "",
        i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;

    return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
}


/**
 * Método responsável por receber um string, remover os
 * caracteres especiais e acentos. E retornar a nova
 * string limpa.
 * -----------------------------------------------------
 * @param string
 * @return {Promise<any>}
 */
function limparString(string) {

    return new Promise(function(resolve){

        // Recupera a string
        var v1 = string;

        // Caracteres a ser substituidos
        var find = ["ã", "à", "á", "ä", "â", "è", "é", "ë", "ê", "ì", "í", "ï", "î", "ò", "ó", "ö", "ô", "ù", "ú", "ü", "û", "ñ", "ç"];
        "à", "á", "ä", "â", "è", "é", "ë", "ê", "ì", "í", "ï", "î", "ò", "ó", "ö", "ô", "ù", "ú", "ü", "û", "ñ", "ç"
        var replace = ["a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "u", "u", "u", "u", "n", "c"];

        // Percorre e substitui os caracteres
        for (var i = 0; i < find.length; i++) {
            v1 = v1.replace(new RegExp(find[i], 'gi'), replace[i]);
        }

        // Remove os especiais
        v1 = v1.replace("?", "");
        v1 = v1.replace("!", "");
        v1 = v1.replace("/", "");
        v1 = v1.replace("\"", "");
        v1 = v1.replace("]", "");
        v1 = v1.replace("[", "");
        v1 = v1.replace(")", "");
        v1 = v1.replace("(", "");
        v1 = v1.replace("^", "");
        v1 = v1.replace(":", "");
        v1 = v1.replace(";", "");
        v1 = v1.replace(",", "");
        v1 = v1.replace(".", "");
        v1 = v1.replace("_", "");
        v1 = v1.replace("º", "");
        v1 = v1.replace("ª", "");
        v1 = v1.replace("#", "");
        v1 = v1.replace("@", "");
        v1 = v1.replace("+", "");
        v1 = v1.replace("%", "");
        v1 = v1.replace("$", "");
        v1 = v1.replace("*", "");
        v1 = v1.replace("£", "");
        v1 = v1.replace("|", "");
        v1 = v1.replace("ÿ", "");
        v1 = v1.replace('"', "");
        v1 = v1.replace("'", "");
        v1 = v1.replace("`", "");
        v1 = v1.replace("´", "");

        // Remove os espaços por ifem
        var desired = v1.replace(/\s+/g, '-');

        // Deixa tudo minusculo
        desired = desired.toLowerCase();

        // Retorna a string limpa
        resolve(desired);
    });

} // End >> fun::limparString()


/**
 * Método responsável por limpar todos os campos de
 * um determinado formulário.
 * --------------------------------------------------
 * @param form
 */
function limpaFormulario(form)
{
    // Percorre os campos do form
    $(form).each(function(){
        // Limpa o campo
        this.reset();
    });
}


// Retorno para os demais arquivos
export default (() => {

    // retornando um objeto personalizado (só com o necessário)
    return {
        config: Dados,
        session: new Session(),
        enviaApi: enviaApi,
        setError: error,
        setSuccess: success,
        calculaData: calculaData,
        formatMoney: formatMoney,
        limparString: limparString,
        limparFormulario: limpaFormulario
    };

})();
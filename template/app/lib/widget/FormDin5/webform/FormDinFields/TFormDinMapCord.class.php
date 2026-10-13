<?php
/*
 * ----------------------------------------------------------------------------
 * Formdin 5 Framework
 * SourceCode https://github.com/bjverde/formDin5
 * @author Reinaldo A. Barrêto Junior
 * 
 * É uma reconstrução do FormDin 4 Sobre o Adianti 7.X
 * @author Luís Eugênio Barbosa do FormDin 4
 * 
 * Adianti Framework é uma criação Adianti Solutions Ltd
 * @author Pablo Dall'Oglio
 * ----------------------------------------------------------------------------
 * This file is part of Formdin Framework.
 *
 * Formdin Framework is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License version 3
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License version 3
 * along with this program; if not,  see <http://www.gnu.org/licenses/>
 * or write to the Free Software Foundation, Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA  02110-1301, USA.
 * ----------------------------------------------------------------------------
 * Este arquivo é parte do Framework Formdin.
 *
 * O Framework Formdin é um software livre; você pode redistribuí-lo e/ou
 * modificá-lo dentro dos termos da GNU LGPL versão 3 como publicada pela Fundação
 * do Software Livre (FSF).
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA
 * GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou
 * APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/LGPL em português
 * para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da GNU LGPL versão 3, sob o título
 * "LICENCA.txt", junto com esse programa. Se não, acesse <http://www.gnu.org/licenses/>
 * ou escreva para a Fundação do Software Livre (FSF) Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02111-1301, USA.
 * ----------------------------------------------------------------------------
 */

class TFormDinMapCord extends TFormDinGenericField
{
    const DECIMAL_PLACES = 6;

    protected $adiantiObj;
    private $adiantiForm = null;
    private $idDivMap = null;
    private $showFields = null;
    private $fieldsReadOnly = null;
    private $defaultLat = null;
    private $defaultLon = null;
    private $zoom = null;
    private $height = null;
    private $geoJsonPath = null;
    private $decimalPlaces = null;
    private $decimalsSeparator = null;
    private $fieldNameLat = null;
    private $fieldNameLon = null;
    private $adiantiFieldLat = null;
    private $adiantiFieldLon = null;

    /**
     * Geolocalização interativa usando o Leaflet.js
     *
     * Exibe um mapa onde o usuário clica ou arrasta o marcador para informar
     * a coordenada. O valor fica em dois campos internos, latitude e longitude,
     * que podem ser visíveis (TNumeric) ou ocultos (THidden).
     *
     * Nome dos campos internos:
     *  - Default: {idField}_lat e {idField}_lon
     *  - Com $fieldNameLat e $fieldNameLon é possível usar outros nomes, por
     *    exemplo os nomes das colunas no banco, para o getData() já vir pronto
     *    para o onSave
     *
     * Os campos internos são registrados automaticamente no $adiantiForm,
     * para aparecerem no getData(). Não chame $adiantiForm->addField() para
     * eles, o Adianti lança exceção de campo duplicado.
     *
     * Todo o HTML/JS é montado no construtor. Por isso as opções devem ser
     * informadas aqui. Só setDecimalPlaces() e setDecimalsSeparator()
     * continuam funcionando se chamados depois do construtor.
     *
     * Exemplos:
     *   // padrão: campos mapcord_lat e mapcord_lon
     *   $map = new TFormDinMapCord($this->form, 'mapcord', 'Coordenadas');
     *
     *   // parâmetros nomeados, com nomes das colunas do banco e vírgula decimal
     *   $map = new TFormDinMapCord($this->form, 'mapcord', 'Coordenadas'
     *                             ,boolRequired: true
     *                             ,decimalsSeparator: ','
     *                             ,fieldNameLat: 'nu_latitude'
     *                             ,fieldNameLon: 'nu_longitude');
     *   $this->form->addFields([$map->getLabel()], [$map->getAdiantiObj()]);
     *
     * @param BootstrapFormBuilder $adiantiForm -01: Form Adianti onde os campos lat/lon serão registrados
     * @param string  $idField         -02: ID do componente. Base do id da div do mapa e, por padrão, do nome dos campos lat/lon
     * @param string  $label           -03: Label do campo, usado para validações
     * @param boolean $boolRequired    -04: Campo obrigatório ou não. Default FALSE
     * @param boolean $showFields      -05: TRUE (Default) or FALSE, Mostrar campos numéricos de lat e lon. FALSE usa campos ocultos
     * @param boolean $fieldsReadOnly  -06: TRUE ou FALSE (Default), Campos somente leitura. TRUE também bloqueia clique e arraste no mapa
     * @param double  $defaultLat      -07: Latitude inicial padrão, usada se o campo estiver vazio. Default -15.793889 (Brasília)
     * @param double  $defaultLon      -08: Longitude inicial padrão, usada se o campo estiver vazio. Default -47.882778 (Brasília)
     * @param int     $zoom            -09: Nível de zoom inicial do mapa. Default 12
     * @param int     $height          -10: Altura do mapa em pixels. Default 400
     * @param string  $geoJsonPath     -11: Caminho para arquivo GeoJSON a ser plotado. Default null
     * @param int     $decimalPlaces   -12: Quantidade de casas decimais de lat e lon. Default 6 (precisão de ~11cm)
     * @param string  $decimalsSeparator -13: Separador decimal na tela, '.' (Default) ou ','. No getData() o valor sempre vem com '.'
     * @param string  $fieldNameLat    -14: Nome do campo de latitude no form/getData(). Default {idField}_lat
     * @param string  $fieldNameLon    -15: Nome do campo de longitude no form/getData(). Default {idField}_lon
     * @throws InvalidArgumentException se $fieldNameLat e $fieldNameLon forem iguais
     * @return TElement
     */
    public function __construct(BootstrapFormBuilder $adiantiForm
                               ,string $idField
                               ,string $label
                               ,$boolRequired  = null
                               ,$showFields    = null
                               ,$fieldsReadOnly= null
                               ,$defaultLat    = null
                               ,$defaultLon    = null
                               ,$zoom          = null
                               ,$height        = null
                               ,$geoJsonPath   = null
                               ,$decimalPlaces = null
                               ,$decimalsSeparator = null
                               ,$fieldNameLat  = null
                               ,$fieldNameLon  = null
                               )
    {
        $this->setAdiantiForm($adiantiForm);
        $this->setIdDivMap($idField);
        $this->setFieldNames($idField, $fieldNameLat, $fieldNameLon);
        $this->setShowFields($showFields);
        $this->setFieldsReadOnly($fieldsReadOnly);
        $this->setDefaultLat($defaultLat);
        $this->setDefaultLon($defaultLon);
        $this->setZoom($zoom);
        $this->setHeight($height);
        $this->setGeoJsonPath($geoJsonPath);
        $this->setDecimalPlaces($decimalPlaces);
        $this->setDecimalsSeparator($decimalsSeparator);

        $adiantiObj = $this->getDivMapElement($idField, $boolRequired);
        parent::__construct($adiantiObj, $this->getIdDivMap(), $label, false, null, null);
        $this->setLabel($label, $boolRequired);
        $this->registerChildFields();

        return $this->getAdiantiObj();
    }

    //--------------------------------------------------------------------
    public function setAdiantiForm(BootstrapFormBuilder $adiantiForm)
    {
        $this->adiantiForm = $adiantiForm;
    }
    public function getAdiantiForm()
    {
        return $this->adiantiForm;
    }

    /**
     * O objeto principal é uma TElement (div), que não é registrada pelo
     * BootstrapFormBuilder::addFields. Por isso os campos internos são
     * registrados no form aqui, senão não aparecem no getData()
     */
    private function registerChildFields()
    {
        foreach ($this->getAdiantiChildFields() as $childField) {
            $this->getAdiantiForm()->addField($childField);
        }
    }

    //--------------------------------------------------------------------
    public function setGeoJsonPath($geoJsonPath)
    {
        $this->geoJsonPath = $geoJsonPath;
    }
    public function getGeoJsonPath()
    {
        return $this->geoJsonPath;
    }

    //--------------------------------------------------------------------
    public function setIdDivMap($idDivMap)
    {
        $this->idDivMap = $idDivMap;
    }
    public function getIdDivMap()
    {
        return $this->idDivMap;
    }

    //--------------------------------------------------------------------
    public function setShowFields($showFields)
    {
        $this->showFields = is_null($showFields) ? true : (bool)$showFields;
    }
    public function getShowFields()
    {
        return $this->showFields;
    }

    //--------------------------------------------------------------------
    public function setFieldsReadOnly($fieldsReadOnly)
    {
        $this->fieldsReadOnly = is_null($fieldsReadOnly) ? false : (bool)$fieldsReadOnly;
    }
    public function getFieldsReadOnly()
    {
        return $this->fieldsReadOnly;
    }

    //--------------------------------------------------------------------
    public function setDefaultLat($defaultLat)
    {
        $this->defaultLat = is_null($defaultLat) ? -15.793889 : (float)$defaultLat;
    }
    public function getDefaultLat()
    {
        return $this->defaultLat;
    }

    //--------------------------------------------------------------------
    public function setDefaultLon($defaultLon)
    {
        $this->defaultLon = is_null($defaultLon) ? -47.882778 : (float)$defaultLon;
    }
    public function getDefaultLon()
    {
        return $this->defaultLon;
    }

    //--------------------------------------------------------------------
    public function setZoom($zoom)
    {
        $this->zoom = is_null($zoom) ? 12 : (int)$zoom;
    }
    public function getZoom()
    {
        return $this->zoom;
    }

    //--------------------------------------------------------------------
    public function setHeight($height)
    {
        $this->height = is_null($height) ? 400 : (int)$height;
    }
    public function getHeight()
    {
        return $this->height;
    }

    //--------------------------------------------------------------------
    /**
     * Quantidade de casas decimais de latitude e longitude.
     * Pode ser chamado depois do construtor, a máscara é atualizada.
     * @param int $decimalPlaces - Default 6 (precisão de ~11cm)
     */
    public function setDecimalPlaces($decimalPlaces)
    {
        $decimalPlaces = is_null($decimalPlaces) ? self::DECIMAL_PLACES : (int)$decimalPlaces;
        if ($decimalPlaces < 0) {
            throw new InvalidArgumentException('decimalPlaces deve ser maior ou igual a zero');
        }
        $this->decimalPlaces = $decimalPlaces;
        $this->applyNumericFormat();
    }
    public function getDecimalPlaces()
    {
        return $this->decimalPlaces;
    }

    /**
     * Separador decimal exibido na tela: '.' (Default) ou ','.
     * O getData() sempre devolve o valor com '.', pronto para gravar no banco.
     * Pode ser chamado depois do construtor, a máscara é atualizada.
     * @param string $decimalsSeparator
     */
    public function setDecimalsSeparator($decimalsSeparator)
    {
        $comma = TFormDinNumericField::COMMA;
        $this->decimalsSeparator = ($decimalsSeparator === $comma) ? $comma : TFormDinNumericField::DOT;
        $this->applyNumericFormat();
    }
    public function getDecimalsSeparator()
    {
        return $this->decimalsSeparator;
    }

    /**
     * Separador usado de fato nos campos. Campos ocultos não têm máscara
     * e são postados como estão, por isso usam sempre '.'
     * @return string
     */
    private function getFieldDecimalsSeparator()
    {
        return $this->getShowFields() ? $this->getDecimalsSeparator() : TFormDinNumericField::DOT;
    }

    /**
     * Aplica casas decimais e separador nos campos lat/lon e na div do mapa,
     * de onde o FormDin5MapCord.js lê a formatação
     */
    private function applyNumericFormat()
    {
        $separator = $this->getFieldDecimalsSeparator();
        foreach ([$this->adiantiFieldLat, $this->adiantiFieldLon] as $field) {
            if ($field instanceof TNumeric) {
                $field->setNumericMask($this->getDecimalPlaces(), $separator, '', true);
            }
        }
        if ($this->adiantiObj instanceof TElement) {
            $this->adiantiObj->setProperty('data-decimals', $this->getDecimalPlaces());
            $this->adiantiObj->setProperty('data-separator', $separator);
        }
    }

    //--------------------------------------------------------------------
    /**
     * Define o nome dos campos lat/lon. Privado porque o nome é usado ao
     * criar os campos, registrar no form e iniciar o JS, tudo no construtor
     */
    private function setFieldNames($idField, $fieldNameLat, $fieldNameLon)
    {
        $fieldNameLat = is_null($fieldNameLat) ? '' : trim($fieldNameLat);
        $fieldNameLon = is_null($fieldNameLon) ? '' : trim($fieldNameLon);
        $fieldNameLat = ($fieldNameLat === '') ? $idField . '_lat' : $fieldNameLat;
        $fieldNameLon = ($fieldNameLon === '') ? $idField . '_lon' : $fieldNameLon;
        if ($fieldNameLat === $fieldNameLon) {
            throw new InvalidArgumentException('fieldNameLat e fieldNameLon devem ser diferentes: ' . $fieldNameLat);
        }
        $this->fieldNameLat = $fieldNameLat;
        $this->fieldNameLon = $fieldNameLon;
    }
    /**
     * Nome do campo de latitude no form/getData(). Default {idField}_lat
     * @return string
     */
    public function getFieldNameLat()
    {
        return $this->fieldNameLat;
    }
    /**
     * Nome do campo de longitude no form/getData(). Default {idField}_lon
     * @return string
     */
    public function getFieldNameLon()
    {
        return $this->fieldNameLon;
    }

    //--------------------------------------------------------------------
    /**
     * Retorna o campo Adianti de Latitude ( getFieldNameLat() )
     * @return TField
     */
    public function getAdiantiFieldLat()
    {
        return $this->adiantiFieldLat;
    }
    /**
     * Retorna o campo Adianti de Longitude ( getFieldNameLon() )
     * @return TField
     */
    public function getAdiantiFieldLon()
    {
        return $this->adiantiFieldLon;
    }
    /**
     * Retorna a lista de campos Adianti internos do componente.
     * @return array
     */
    public function getAdiantiChildFields()
    {
        return [$this->getAdiantiFieldLat(), $this->getAdiantiFieldLon()];
    }

    //--------------------------------------------------------------------
    private function getNumericField($idField, $label, $boolRequired, $minValue, $maxValue)
    {
        $numericField = new TFormDinNumericField($idField, $label, 18, $boolRequired, $this->getDecimalPlaces(), false, null, $minValue, $maxValue, false, null, null, null, null, null, null, true, null, $this->getFieldDecimalsSeparator());
        if ($this->getFieldsReadOnly()) {
            $numericField->setReadOnly(true);
        }
        return $numericField->getAdiantiObj();
    }

    private function getHiddenField($idField, $boolRequired)
    {
        $hiddenField = new TFormDinHiddenField($idField, null, $boolRequired);
        if ($this->getFieldsReadOnly()) {
            $hiddenField->setReadOnly(true);
        }
        return $hiddenField->getAdiantiObj();
    }

    //--------------------------------------------------------------------
    private function getDivMapElement($idField, $boolRequired)
    {
        $divWrapper = new TElement('div');
        $divWrapper->class = 'fd5DivMapCordWrapper';
        $divWrapper->setProperty('id', $this->getIdDivMap() . '_mapwrapper');
        $divWrapper->setProperty('data-decimals', $this->getDecimalPlaces());
        $divWrapper->setProperty('data-separator', $this->getFieldDecimalsSeparator());

        // Cria o elemento da DIV do mapa
        $divMap = new TElement('div');
        $divMap->setProperty('id', $idField . '_map');
        $divMap->setProperty('style', 'height: ' . $this->getHeight() . 'px; width: 100%; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px; position: relative; z-index: 1;');

        // Elementos de importação de CSS e JS locais do Leaflet
        $linkCss = new TElement('link');
        $linkCss->setProperty('rel', 'stylesheet');
        $linkCss->setProperty('href', 'app/lib/widget/FormDin5/leaflet/leaflet.css');

        $scriptJsLeaflet = new TElement('script');
        $scriptJsLeaflet->setProperty('src', 'app/lib/widget/FormDin5/leaflet/leaflet.js');

        $scriptJsMap = new TElement('script');
        $scriptJsMap->setProperty('src', 'app/lib/widget/FormDin5/javascript/FormDin5MapCord.js?appver=' . FormDinHelper::version());

        // Campos de inputs de Latitude e Longitude
        $adiantiObjLat = null;
        $adiantiObjLon = null;
        if ($this->getShowFields() == true) {
            $adiantiObjLat = $this->getNumericField($this->getFieldNameLat(), 'Latitude', $boolRequired, -90, 90);
            $adiantiObjLon = $this->getNumericField($this->getFieldNameLon(), 'Longitude', $boolRequired, -180, 180);
        } else {
            $adiantiObjLat = $this->getHiddenField($this->getFieldNameLat(), $boolRequired);
            $adiantiObjLon = $this->getHiddenField($this->getFieldNameLon(), $boolRequired);
        }
        $this->adiantiFieldLat = $adiantiObjLat;
        $this->adiantiFieldLon = $adiantiObjLon;

        // Script inline para inicialização do mapa de maneira assíncrona/segura
        $scriptInit = new TElement('script');
        $readOnlyStr = $this->getFieldsReadOnly() ? 'true' : 'false';
        $geoJsonPathStr = $this->getGeoJsonPath() ? json_encode($this->getGeoJsonPath()) : 'null';
        $fieldLatStr = json_encode($this->getFieldNameLat());
        $fieldLonStr = json_encode($this->getFieldNameLon());
        $initArgs = "'{$idField}', {$this->getDefaultLat()}, {$this->getDefaultLon()}, {$this->getZoom()}, {$readOnlyStr}, {$geoJsonPathStr}, {$fieldLatStr}, {$fieldLonStr}";
        $scriptInit->add("
            setTimeout(function() {
                if (typeof fd5InitMap === 'function') {
                    fd5InitMap({$initArgs});
                } else {
                    let checkInterval = setInterval(function() {
                        if (typeof fd5InitMap === 'function') {
                            clearInterval(checkInterval);
                            fd5InitMap({$initArgs});
                        }
                    }, 100);
                }
            }, 100);
        ");

        // Adiciona todos os componentes ao container wrapper
        $divWrapper->add($linkCss);
        $divWrapper->add($scriptJsLeaflet);
        $divWrapper->add($scriptJsMap);
        $divWrapper->add($divMap);
        $divWrapper->add($adiantiObjLat);
        $divWrapper->add($adiantiObjLon);
        $divWrapper->add($scriptInit);

        return $divWrapper;
    }
}

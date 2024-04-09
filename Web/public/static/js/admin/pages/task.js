/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/jquery-repeater-form/jquery.repeater.js":
/*!**************************************************************!*\
  !*** ./node_modules/jquery-repeater-form/jquery.repeater.js ***!
  \**************************************************************/
/***/ (() => {

// jquery.repeater version 1.2.1
// https://github.com/DubFriend/jquery.repeater
// (MIT) 09-10-2016
// Brian Detering <BDeterin@gmail.com> (http://www.briandetering.net/)
(function ($) {
'use strict';

var identity = function (x) {
    return x;
};

var isArray = function (value) {
    return $.isArray(value);
};

var isObject = function (value) {
    return !isArray(value) && (value instanceof Object);
};

var isNumber = function (value) {
    return value instanceof Number;
};

var isFunction = function (value) {
    return value instanceof Function;
};

var indexOf = function (object, value) {
    return $.inArray(value, object);
};

var inArray = function (array, value) {
    return indexOf(array, value) !== -1;
};

var foreach = function (collection, callback) {
    for(var i in collection) {
        if(collection.hasOwnProperty(i)) {
            callback(collection[i], i, collection);
        }
    }
};


var last = function (array) {
    return array[array.length - 1];
};

var argumentsToArray = function (args) {
    return Array.prototype.slice.call(args);
};

var extend = function () {
    var extended = {};
    foreach(argumentsToArray(arguments), function (o) {
        foreach(o, function (val, key) {
            extended[key] = val;
        });
    });
    return extended;
};

var mapToArray = function (collection, callback) {
    var mapped = [];
    foreach(collection, function (value, key, coll) {
        mapped.push(callback(value, key, coll));
    });
    return mapped;
};

var mapToObject = function (collection, callback, keyCallback) {
    var mapped = {};
    foreach(collection, function (value, key, coll) {
        key = keyCallback ? keyCallback(key, value) : key;
        mapped[key] = callback(value, key, coll);
    });
    return mapped;
};

var map = function (collection, callback, keyCallback) {
    return isArray(collection) ?
        mapToArray(collection, callback) :
        mapToObject(collection, callback, keyCallback);
};

var pluck = function (arrayOfObjects, key) {
    return map(arrayOfObjects, function (val) {
        return val[key];
    });
};

var filter = function (collection, callback) {
    var filtered;

    if(isArray(collection)) {
        filtered = [];
        foreach(collection, function (val, key, coll) {
            if(callback(val, key, coll)) {
                filtered.push(val);
            }
        });
    }
    else {
        filtered = {};
        foreach(collection, function (val, key, coll) {
            if(callback(val, key, coll)) {
                filtered[key] = val;
            }
        });
    }

    return filtered;
};

var call = function (collection, functionName, args) {
    return map(collection, function (object, name) {
        return object[functionName].apply(object, args || []);
    });
};

//execute callback immediately and at most one time on the minimumInterval,
//ignore block attempts
var throttle = function (minimumInterval, callback) {
    var timeout = null;
    return function () {
        var that = this, args = arguments;
        if(timeout === null) {
            timeout = setTimeout(function () {
                timeout = null;
            }, minimumInterval);
            callback.apply(that, args);
        }
    };
};


var mixinPubSub = function (object) {
    object = object || {};
    var topics = {};

    object.publish = function (topic, data) {
        foreach(topics[topic], function (callback) {
            callback(data);
        });
    };

    object.subscribe = function (topic, callback) {
        topics[topic] = topics[topic] || [];
        topics[topic].push(callback);
    };

    object.unsubscribe = function (callback) {
        foreach(topics, function (subscribers) {
            var index = indexOf(subscribers, callback);
            if(index !== -1) {
                subscribers.splice(index, 1);
            }
        });
    };

    return object;
};

// jquery.input version 0.0.0
// https://github.com/DubFriend/jquery.input
// (MIT) 09-04-2014
// Brian Detering <BDeterin@gmail.com> (http://www.briandetering.net/)
(function ($) {
'use strict';

var createBaseInput = function (fig, my) {
    var self = mixinPubSub(),
        $self = fig.$;

    self.getType = function () {
        throw 'implement me (return type. "text", "radio", etc.)';
    };

    self.$ = function (selector) {
        return selector ? $self.find(selector) : $self;
    };

    self.disable = function () {
        self.$().prop('disabled', true);
        self.publish('isEnabled', false);
    };

    self.enable = function () {
        self.$().prop('disabled', false);
        self.publish('isEnabled', true);
    };

    my.equalTo = function (a, b) {
        return a === b;
    };

    my.publishChange = (function () {
        var oldValue;
        return function (e, domElement) {
            var newValue = self.get();
            if(!my.equalTo(newValue, oldValue)) {
                self.publish('change', { e: e, domElement: domElement });
            }
            oldValue = newValue;
        };
    }());

    return self;
};


var createInput = function (fig, my) {
    var self = createBaseInput(fig, my);

    self.get = function () {
        return self.$().val();
    };

    self.set = function (newValue) {
        self.$().val(newValue);
    };

    self.clear = function () {
        self.set('');
    };

    my.buildSetter = function (callback) {
        return function (newValue) {
            callback.call(self, newValue);
        };
    };

    return self;
};

var inputEqualToArray = function (a, b) {
    a = isArray(a) ? a : [a];
    b = isArray(b) ? b : [b];

    var isEqual = true;
    if(a.length !== b.length) {
        isEqual = false;
    }
    else {
        foreach(a, function (value) {
            if(!inArray(b, value)) {
                isEqual = false;
            }
        });
    }

    return isEqual;
};

var createInputButton = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'button';
    };

    self.$().on('change', function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputCheckbox = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'checkbox';
    };

    self.get = function () {
        var values = [];
        self.$().filter(':checked').each(function () {
            values.push($(this).val());
        });
        return values;
    };

    self.set = function (newValues) {
        newValues = isArray(newValues) ? newValues : [newValues];

        self.$().each(function () {
            $(this).prop('checked', false);
        });

        foreach(newValues, function (value) {
            self.$().filter('[value="' + value + '"]')
                .prop('checked', true);
        });
    };

    my.equalTo = inputEqualToArray;

    self.$().change(function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputEmail = function (fig) {
    var my = {},
        self = createInputText(fig, my);

    self.getType = function () {
        return 'email';
    };

    return self;
};

var createInputFile = function (fig) {
    var my = {},
        self = createBaseInput(fig, my);

    self.getType = function () {
        return 'file';
    };

    self.get = function () {
        return last(self.$().val().split('\\'));
    };

    self.clear = function () {
        // http://stackoverflow.com/questions/1043957/clearing-input-type-file-using-jquery
        this.$().each(function () {
            $(this).wrap('<form>').closest('form').get(0).reset();
            $(this).unwrap();
        });
    };

    self.$().change(function (e) {
        my.publishChange(e, this);
        // self.publish('change', self);
    });

    return self;
};

var createInputHidden = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'hidden';
    };

    self.$().change(function (e) {
        my.publishChange(e, this);
    });

    return self;
};
var createInputMultipleFile = function (fig) {
    var my = {},
        self = createBaseInput(fig, my);

    self.getType = function () {
        return 'file[multiple]';
    };

    self.get = function () {
        // http://stackoverflow.com/questions/14035530/how-to-get-value-of-html-5-multiple-file-upload-variable-using-jquery
        var fileListObject = self.$().get(0).files || [],
            names = [], i;

        for(i = 0; i < (fileListObject.length || 0); i += 1) {
            names.push(fileListObject[i].name);
        }

        return names;
    };

    self.clear = function () {
        // http://stackoverflow.com/questions/1043957/clearing-input-type-file-using-jquery
        this.$().each(function () {
            $(this).wrap('<form>').closest('form').get(0).reset();
            $(this).unwrap();
        });
    };

    self.$().change(function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputMultipleSelect = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'select[multiple]';
    };

    self.get = function () {
        return self.$().val() || [];
    };

    self.set = function (newValues) {
        self.$().val(
            newValues === '' ? [] : isArray(newValues) ? newValues : [newValues]
        );
    };

    my.equalTo = inputEqualToArray;

    self.$().change(function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputPassword = function (fig) {
    var my = {},
        self = createInputText(fig, my);

    self.getType = function () {
        return 'password';
    };

    return self;
};

var createInputRadio = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'radio';
    };

    self.get = function () {
        return self.$().filter(':checked').val() || null;
    };

    self.set = function (newValue) {
        if(!newValue) {
            self.$().each(function () {
                $(this).prop('checked', false);
            });
        }
        else {
            self.$().filter('[value="' + newValue + '"]').prop('checked', true);
        }
    };

    self.$().change(function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputRange = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'range';
    };

    self.$().change(function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputSelect = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'select';
    };

    self.$().change(function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputText = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'text';
    };

    self.$().on('change keyup keydown', function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputTextarea = function (fig) {
    var my = {},
        self = createInput(fig, my);

    self.getType = function () {
        return 'textarea';
    };

    self.$().on('change keyup keydown', function (e) {
        my.publishChange(e, this);
    });

    return self;
};

var createInputURL = function (fig) {
    var my = {},
        self = createInputText(fig, my);

    self.getType = function () {
        return 'url';
    };

    return self;
};

var buildFormInputs = function (fig) {
    var inputs = {},
        $self = fig.$;

    var constructor = fig.constructorOverride || {
        button: createInputButton,
        text: createInputText,
        url: createInputURL,
        email: createInputEmail,
        password: createInputPassword,
        range: createInputRange,
        textarea: createInputTextarea,
        select: createInputSelect,
        'select[multiple]': createInputMultipleSelect,
        radio: createInputRadio,
        checkbox: createInputCheckbox,
        file: createInputFile,
        'file[multiple]': createInputMultipleFile,
        hidden: createInputHidden
    };

    var addInputsBasic = function (type, selector) {
        var $input = isObject(selector) ? selector : $self.find(selector);

        $input.each(function () {
            var name = $(this).attr('name');
            inputs[name] = constructor[type]({
                $: $(this)
            });
        });
    };

    var addInputsGroup = function (type, selector) {
        var names = [],
            $input = isObject(selector) ? selector : $self.find(selector);

        if(isObject(selector)) {
            inputs[$input.attr('name')] = constructor[type]({
                $: $input
            });
        }
        else {
            // group by name attribute
            $input.each(function () {
                if(indexOf(names, $(this).attr('name')) === -1) {
                    names.push($(this).attr('name'));
                }
            });

            foreach(names, function (name) {
                inputs[name] = constructor[type]({
                    $: $self.find('input[name="' + name + '"]')
                });
            });
        }
    };


    if($self.is('input, select, textarea')) {
        if($self.is('input[type="button"], button, input[type="submit"]')) {
            addInputsBasic('button', $self);
        }
        else if($self.is('textarea')) {
            addInputsBasic('textarea', $self);
        }
        else if(
            $self.is('input[type="text"]') ||
            $self.is('input') && !$self.attr('type')
        ) {
            addInputsBasic('text', $self);
        }
        else if($self.is('input[type="password"]')) {
            addInputsBasic('password', $self);
        }
        else if($self.is('input[type="email"]')) {
            addInputsBasic('email', $self);
        }
        else if($self.is('input[type="url"]')) {
            addInputsBasic('url', $self);
        }
        else if($self.is('input[type="range"]')) {
            addInputsBasic('range', $self);
        }
        else if($self.is('select')) {
            if($self.is('[multiple]')) {
                addInputsBasic('select[multiple]', $self);
            }
            else {
                addInputsBasic('select', $self);
            }
        }
        else if($self.is('input[type="file"]')) {
            if($self.is('[multiple]')) {
                addInputsBasic('file[multiple]', $self);
            }
            else {
                addInputsBasic('file', $self);
            }
        }
        else if($self.is('input[type="hidden"]')) {
            addInputsBasic('hidden', $self);
        }
        else if($self.is('input[type="radio"]')) {
            addInputsGroup('radio', $self);
        }
        else if($self.is('input[type="checkbox"]')) {
            addInputsGroup('checkbox', $self);
        }
        else {
            //in all other cases default to a "text" input interface.
            addInputsBasic('text', $self);
        }
    }
    else {
        addInputsBasic('button', 'input[type="button"], button, input[type="submit"]');
        addInputsBasic('text', 'input[type="text"]');
        addInputsBasic('password', 'input[type="password"]');
        addInputsBasic('email', 'input[type="email"]');
        addInputsBasic('url', 'input[type="url"]');
        addInputsBasic('range', 'input[type="range"]');
        addInputsBasic('textarea', 'textarea');
        addInputsBasic('select', 'select:not([multiple])');
        addInputsBasic('select[multiple]', 'select[multiple]');
        addInputsBasic('file', 'input[type="file"]:not([multiple])');
        addInputsBasic('file[multiple]', 'input[type="file"][multiple]');
        addInputsBasic('hidden', 'input[type="hidden"]');
        addInputsGroup('radio', 'input[type="radio"]');
        addInputsGroup('checkbox', 'input[type="checkbox"]');
    }

    return inputs;
};

$.fn.inputVal = function (newValue) {
    var $self = $(this);

    var inputs = buildFormInputs({ $: $self });

    if($self.is('input, textarea, select')) {
        if(typeof newValue === 'undefined') {
            return inputs[$self.attr('name')].get();
        }
        else {
            inputs[$self.attr('name')].set(newValue);
            return $self;
        }
    }
    else {
        if(typeof newValue === 'undefined') {
            return call(inputs, 'get');
        }
        else {
            foreach(newValue, function (value, inputName) {
                inputs[inputName].set(value);
            });
            return $self;
        }
    }
};

$.fn.inputOnChange = function (callback) {
    var $self = $(this);
    var inputs = buildFormInputs({ $: $self });
    foreach(inputs, function (input) {
        input.subscribe('change', function (data) {
            callback.call(data.domElement, data.e);
        });
    });
    return $self;
};

$.fn.inputDisable = function () {
    var $self = $(this);
    call(buildFormInputs({ $: $self }), 'disable');
    return $self;
};

$.fn.inputEnable = function () {
    var $self = $(this);
    call(buildFormInputs({ $: $self }), 'enable');
    return $self;
};

$.fn.inputClear = function () {
    var $self = $(this);
    call(buildFormInputs({ $: $self }), 'clear');
    return $self;
};

}(jQuery));

$.fn.repeaterVal = function () {
    var parse = function (raw) {
        var parsed = [];

        foreach(raw, function (val, key) {
            var parsedKey = [];
            if(key !== "undefined") {
                parsedKey.push(key.match(/^[^\[]*/)[0]);
                parsedKey = parsedKey.concat(map(
                    key.match(/\[[^\]]*\]/g),
                    function (bracketed) {
                        return bracketed.replace(/[\[\]]/g, '');
                    }
                ));

                parsed.push({
                    val: val,
                    key: parsedKey
                });
            }
        });

        return parsed;
    };

    var build = function (parsed) {
        if(
            parsed.length === 1 &&
            (parsed[0].key.length === 0 || parsed[0].key.length === 1 && !parsed[0].key[0])
        ) {
            return parsed[0].val;
        }

        foreach(parsed, function (p) {
            p.head = p.key.shift();
        });

        var grouped = (function () {
            var grouped = {};

            foreach(parsed, function (p) {
                if(!grouped[p.head]) {
                    grouped[p.head] = [];
                }
                grouped[p.head].push(p);
            });

            return grouped;
        }());

        var built;

        if(/^[0-9]+$/.test(parsed[0].head)) {
            built = [];
            foreach(grouped, function (group) {
                built.push(build(group));
            });
        }
        else {
            built = {};
            foreach(grouped, function (group, key) {
                built[key] = build(group);
            });
        }

        return built;
    };

    return build(parse($(this).inputVal()));
};

$.fn.repeater = function (fig) {
    fig = fig || {};

    var setList;
    var setOption;
    var destory;

    $(this).each(function () {

        var $self = $(this);

        var show = fig.show || function () {
            $(this).show();
        };

        var hide = fig.hide || function (removeElement) {
            removeElement();
        };

        var $list = $self.find('[data-repeater-list]').first();

        var $filterNested = function ($items, repeaters) {
            return $items.filter(function () {
                return repeaters ?
                    $(this).closest(
                        pluck(repeaters, 'selector').join(',')
                    ).length === 0 : true;
            });
        };

        var $items = function () {
            return $filterNested($list.find('[data-repeater-item]'), fig.repeaters);
        };

        var $itemTemplate = $list.find('[data-repeater-item]')
                                 .first().clone().hide();

        var $firstDeleteButton = $filterNested(
            $filterNested($(this).find('[data-repeater-item]'), fig.repeaters)
            .first().find('[data-repeater-delete]'),
            fig.repeaters
        );

        if(fig.isFirstItemUndeletable && $firstDeleteButton) {
            $firstDeleteButton.remove();
        }

        var getGroupName = function () {
            var groupName = $list.data('repeater-list');
            return fig.$parent ?
                fig.$parent.data('item-name') + '[' + groupName + ']' :
                groupName;
        };

        var initNested = function ($listItems) {
            if(fig.repeaters) {
                $listItems.each(function () {
                    var $item = $(this);
                    foreach(fig.repeaters, function (nestedFig) {
                        $item.find(nestedFig.selector).repeater(extend(
                            nestedFig, { $parent: $item }
                        ));
                    });
                });
            }
        };

        var $foreachRepeaterInItem = function (repeaters, $item, cb) {
            if(repeaters) {
                foreach(repeaters, function (nestedFig) {
                    cb.call($item.find(nestedFig.selector)[0], nestedFig);
                });
            }
        };

        var setIndexes = function ($items, groupName, repeaters) {
            $items.each(function (index) {
                var $item = $(this);
                $item.data('item-name', groupName + '[' + index + ']');
                $filterNested($item.find('[name]'), repeaters)
                .each(function () {
                    var $input = $(this);
                    // match non empty brackets (ex: "[foo]")
                    var matches = $input.attr('name').match(/\[[^\]]+\]/g);

                    var name = matches ?
                        // strip "[" and "]" characters
                        last(matches).replace(/\[|\]/g, '') :
                        $input.attr('name');


                    var newName = groupName + '[' + index + '][' + name + ']' +
                        ($input.is(':checkbox') || $input.attr('multiple') ? '[]' : '');

                    $input.attr('name', newName);

                    $foreachRepeaterInItem(repeaters, $item, function (nestedFig) {
                        var $repeater = $(this);
                        setIndexes(
                            $filterNested($repeater.find('[data-repeater-item]'), nestedFig.repeaters || []),
                            groupName + '[' + index + ']' +
                                        '[' + $repeater.find('[data-repeater-list]').first().data('repeater-list') + ']',
                            nestedFig.repeaters
                        );
                    });
                });
            });

            $list.find('input[name][checked]')
                .removeAttr('checked')
                .prop('checked', true);
        };

        setIndexes($items(), getGroupName(), fig.repeaters);
        initNested($items());
        if(fig.initEmpty) {
            $items().remove();
        }

        if(fig.ready) {
            fig.ready(function () {
                setIndexes($items(), getGroupName(), fig.repeaters);
            });
        }

        var appendItem = (function () {
            var setItemsValues = function ($item, data, repeaters) {
                if(data || fig.defaultValues) {
                    var inputNames = {};
                    $filterNested($item.find('[name]'), repeaters).each(function () {
                        var key = $(this).attr('name').match(/\[([^\]]*)(\]|\]\[\])$/)[1];
                        inputNames[key] = $(this).attr('name');
                    });
;
                    $item.inputVal(map(
                        filter(data || fig.defaultValues, function (val, name) {
                            return inputNames[name];
                        }),
                        identity,
                        function (name) {
                            return inputNames[name];
                        }
                    ));
                }

                $foreachRepeaterInItem(repeaters, $item, function (nestedFig) {
                    var $repeater = $(this);
                    $filterNested(
                        $repeater.find('[data-repeater-item]'),
                        nestedFig.repeaters
                    )
                    .each(function () {
                        var fieldName = $repeater.find('[data-repeater-list]').data('repeater-list');
                        if(data && data[fieldName]) {
                            var $template = $(this).clone();
                            $repeater.find('[data-repeater-item]').remove();
                            foreach(data[fieldName], function (data) {
                                var $item = $template.clone();
                                setItemsValues(
                                    $item,
                                    data,
                                    nestedFig.repeaters || []
                                );
                                $repeater.find('[data-repeater-list]').append($item);
                            });
                        }
                        else {
                            setItemsValues(
                                $(this),
                                nestedFig.defaultValues,
                                nestedFig.repeaters || []
                            );
                        }
                    });
                });

            };

            return function ($item, data) {
                $list.append($item);
                setIndexes($items(), getGroupName(), fig.repeaters);
                $item.find('[name]').each(function () {
                    $(this).inputClear();
                });
                setItemsValues($item, data || fig.defaultValues, fig.repeaters);
            };
        }());
        var updateOption  = (function () {
            
            return function (data) {
                $.each(data,function(name,data_value){
                    $itemTemplate.find('[name="'+name+'"]').each(function () {
                        if($(this).is('select')){
                        $(this).find('option:not(:first)').remove();
                            var this_select = $(this);
                            $.each(data_value, function(key, item) {
                                this_select.append('<option value="'+item.id+'">'+item.val+'</option>');
                            });
                        }
                    });
                });
            };
         }());    
        var addItem = function (data) {
            var $item = $itemTemplate.clone();
            appendItem($item, data);
            if(fig.repeaters) {
                initNested($item);
            }
            show.call($item.get(0));
        };
        var addOption = function (data) {
            updateOption(data);
            var $item = $itemTemplate.clone();
            appendItem($item, data);
            if(fig.repeaters) {
                initNested($item);
            }
            show.call($item.get(0));
        };

        setList = function (rows) {
            $items().remove();
            foreach(rows, addItem);
        };
        setOption = function (rows) {
            $items().remove();
            foreach(rows, addOption);
        };
        destory = function (rows) {
            $items().remove();
            var $item = $itemTemplate.clone();
            $(this).find('[data-repeater-list]').append($item.show());
            $.fn.repeater={};
        };

        $filterNested($self.find('[data-repeater-create]'), fig.repeaters).click(function () {
            addItem();
        });

        $list.on('click', '[data-repeater-delete]', function () {
            var self = $(this).closest('[data-repeater-item]').get(0);
            hide.call(self, function () {
                $(self).remove();
                setIndexes($items(), getGroupName(), fig.repeaters);
            });
        });
    });

    this.setList = setList;
    this.setOption = setOption;
    this.destory = destory;

    return this;
};

}(jQuery));

/***/ }),

/***/ "./node_modules/just-extend/index.esm.js":
/*!***********************************************!*\
  !*** ./node_modules/just-extend/index.esm.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ objectExtend)
/* harmony export */ });
var objectExtend = extend;

/*
  var obj = {a: 3, b: 5};
  extend(obj, {a: 4, c: 8}); // {a: 4, b: 5, c: 8}
  obj; // {a: 4, b: 5, c: 8}

  var obj = {a: 3, b: 5};
  extend({}, obj, {a: 4, c: 8}); // {a: 4, b: 5, c: 8}
  obj; // {a: 3, b: 5}

  var arr = [1, 2, 3];
  var obj = {a: 3, b: 5};
  extend(obj, {c: arr}); // {a: 3, b: 5, c: [1, 2, 3]}
  arr.push(4);
  obj; // {a: 3, b: 5, c: [1, 2, 3, 4]}

  var arr = [1, 2, 3];
  var obj = {a: 3, b: 5};
  extend(true, obj, {c: arr}); // {a: 3, b: 5, c: [1, 2, 3]}
  arr.push(4);
  obj; // {a: 3, b: 5, c: [1, 2, 3]}

  extend({a: 4, b: 5}); // {a: 4, b: 5}
  extend({a: 4, b: 5}, 3); {a: 4, b: 5}
  extend({a: 4, b: 5}, true); {a: 4, b: 5}
  extend('hello', {a: 4, b: 5}); // throws
  extend(3, {a: 4, b: 5}); // throws
*/

function extend(/* [deep], obj1, obj2, [objn] */) {
  var args = [].slice.call(arguments);
  var deep = false;
  if (typeof args[0] == 'boolean') {
    deep = args.shift();
  }
  var result = args[0];
  if (isUnextendable(result)) {
    throw new Error('extendee must be an object');
  }
  var extenders = args.slice(1);
  var len = extenders.length;
  for (var i = 0; i < len; i++) {
    var extender = extenders[i];
    for (var key in extender) {
      if (Object.prototype.hasOwnProperty.call(extender, key)) {
        var value = extender[key];
        if (deep && isCloneable(value)) {
          var base = Array.isArray(value) ? [] : {};
          result[key] = extend(
            true,
            Object.prototype.hasOwnProperty.call(result, key) && !isUnextendable(result[key])
              ? result[key]
              : base,
            value
          );
        } else {
          result[key] = value;
        }
      }
    }
  }
  return result;
}

function isCloneable(obj) {
  return Array.isArray(obj) || {}.toString.call(obj) == '[object Object]';
}

function isUnextendable(val) {
  return !val || (typeof val != 'object' && typeof val != 'function');
}




/***/ }),

/***/ "./node_modules/dropzone/dist/dropzone.mjs":
/*!*************************************************!*\
  !*** ./node_modules/dropzone/dist/dropzone.mjs ***!
  \*************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Dropzone": () => (/* binding */ $3ed269f2f0fb224b$export$2e2bcd8739ae039),
/* harmony export */   "default": () => (/* binding */ $3ed269f2f0fb224b$export$2e2bcd8739ae039)
/* harmony export */ });
/* harmony import */ var just_extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! just-extend */ "./node_modules/just-extend/index.esm.js");


function $parcel$interopDefault(a) {
  return a && a.__esModule ? a.default : a;
}

class $4040acfd8584338d$export$2e2bcd8739ae039 {
    // Add an event listener for given event
    on(event, fn) {
        this._callbacks = this._callbacks || {
        };
        // Create namespace for this event
        if (!this._callbacks[event]) this._callbacks[event] = [];
        this._callbacks[event].push(fn);
        return this;
    }
    emit(event, ...args) {
        this._callbacks = this._callbacks || {
        };
        let callbacks = this._callbacks[event];
        if (callbacks) for (let callback of callbacks)callback.apply(this, args);
        // trigger a corresponding DOM event
        if (this.element) this.element.dispatchEvent(this.makeEvent("dropzone:" + event, {
            args: args
        }));
        return this;
    }
    makeEvent(eventName, detail) {
        let params = {
            bubbles: true,
            cancelable: true,
            detail: detail
        };
        if (typeof window.CustomEvent === "function") return new CustomEvent(eventName, params);
        else {
            // IE 11 support
            // https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent/CustomEvent
            var evt = document.createEvent("CustomEvent");
            evt.initCustomEvent(eventName, params.bubbles, params.cancelable, params.detail);
            return evt;
        }
    }
    // Remove event listener for given event. If fn is not provided, all event
    // listeners for that event will be removed. If neither is provided, all
    // event listeners will be removed.
    off(event, fn) {
        if (!this._callbacks || arguments.length === 0) {
            this._callbacks = {
            };
            return this;
        }
        // specific event
        let callbacks = this._callbacks[event];
        if (!callbacks) return this;
        // remove all handlers
        if (arguments.length === 1) {
            delete this._callbacks[event];
            return this;
        }
        // remove specific handler
        for(let i = 0; i < callbacks.length; i++){
            let callback = callbacks[i];
            if (callback === fn) {
                callbacks.splice(i, 1);
                break;
            }
        }
        return this;
    }
}



var $fd6031f88dce2e32$exports = {};
$fd6031f88dce2e32$exports = "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail=\"\"></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size=\"\"></span></div>\n    <div class=\"dz-filename\"><span data-dz-name=\"\"></span></div>\n  </div>\n  <div class=\"dz-progress\">\n    <span class=\"dz-upload\" data-dz-uploadprogress=\"\"></span>\n  </div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage=\"\"></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54\" height=\"54\" viewBox=\"0 0 54 54\" fill=\"white\" xmlns=\"http://www.w3.org/2000/svg\">\n      <path d=\"M10.2071 29.7929L14.2929 25.7071C14.6834 25.3166 15.3166 25.3166 15.7071 25.7071L21.2929 31.2929C21.6834 31.6834 22.3166 31.6834 22.7071 31.2929L38.2929 15.7071C38.6834 15.3166 39.3166 15.3166 39.7071 15.7071L43.7929 19.7929C44.1834 20.1834 44.1834 20.8166 43.7929 21.2071L22.7071 42.2929C22.3166 42.6834 21.6834 42.6834 21.2929 42.2929L10.2071 31.2071C9.81658 30.8166 9.81658 30.1834 10.2071 29.7929Z\"></path>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54\" height=\"54\" viewBox=\"0 0 54 54\" fill=\"white\" xmlns=\"http://www.w3.org/2000/svg\">\n      <path d=\"M26.2929 20.2929L19.2071 13.2071C18.8166 12.8166 18.1834 12.8166 17.7929 13.2071L13.2071 17.7929C12.8166 18.1834 12.8166 18.8166 13.2071 19.2071L20.2929 26.2929C20.6834 26.6834 20.6834 27.3166 20.2929 27.7071L13.2071 34.7929C12.8166 35.1834 12.8166 35.8166 13.2071 36.2071L17.7929 40.7929C18.1834 41.1834 18.8166 41.1834 19.2071 40.7929L26.2929 33.7071C26.6834 33.3166 27.3166 33.3166 27.7071 33.7071L34.7929 40.7929C35.1834 41.1834 35.8166 41.1834 36.2071 40.7929L40.7929 36.2071C41.1834 35.8166 41.1834 35.1834 40.7929 34.7929L33.7071 27.7071C33.3166 27.3166 33.3166 26.6834 33.7071 26.2929L40.7929 19.2071C41.1834 18.8166 41.1834 18.1834 40.7929 17.7929L36.2071 13.2071C35.8166 12.8166 35.1834 12.8166 34.7929 13.2071L27.7071 20.2929C27.3166 20.6834 26.6834 20.6834 26.2929 20.2929Z\"></path>\n    </svg>\n  </div>\n</div>\n";


let $4ca367182776f80b$var$defaultOptions = {
    /**
   * Has to be specified on elements other than form (or when the form doesn't
   * have an `action` attribute).
   *
   * You can also provide a function that will be called with `files` and
   * `dataBlocks`  and must return the url as string.
   */ url: null,
    /**
   * Can be changed to `"put"` if necessary. You can also provide a function
   * that will be called with `files` and must return the method (since `v3.12.0`).
   */ method: "post",
    /**
   * Will be set on the XHRequest.
   */ withCredentials: false,
    /**
   * The timeout for the XHR requests in milliseconds (since `v4.4.0`).
   * If set to null or 0, no timeout is going to be set.
   */ timeout: null,
    /**
   * How many file uploads to process in parallel (See the
   * Enqueuing file uploads documentation section for more info)
   */ parallelUploads: 2,
    /**
   * Whether to send multiple files in one request. If
   * this it set to true, then the fallback file input element will
   * have the `multiple` attribute as well. This option will
   * also trigger additional events (like `processingmultiple`). See the events
   * documentation section for more information.
   */ uploadMultiple: false,
    /**
   * Whether you want files to be uploaded in chunks to your server. This can't be
   * used in combination with `uploadMultiple`.
   *
   * See [chunksUploaded](#config-chunksUploaded) for the callback to finalise an upload.
   */ chunking: false,
    /**
   * If `chunking` is enabled, this defines whether **every** file should be chunked,
   * even if the file size is below chunkSize. This means, that the additional chunk
   * form data will be submitted and the `chunksUploaded` callback will be invoked.
   */ forceChunking: false,
    /**
   * If `chunking` is `true`, then this defines the chunk size in bytes.
   */ chunkSize: 2097152,
    /**
   * If `true`, the individual chunks of a file are being uploaded simultaneously.
   */ parallelChunkUploads: false,
    /**
   * Whether a chunk should be retried if it fails.
   */ retryChunks: false,
    /**
   * If `retryChunks` is true, how many times should it be retried.
   */ retryChunksLimit: 3,
    /**
   * The maximum filesize (in MiB) that is allowed to be uploaded.
   */ maxFilesize: 256,
    /**
   * The name of the file param that gets transferred.
   * **NOTE**: If you have the option  `uploadMultiple` set to `true`, then
   * Dropzone will append `[]` to the name.
   */ paramName: "file",
    /**
   * Whether thumbnails for images should be generated
   */ createImageThumbnails: true,
    /**
   * In MB. When the filename exceeds this limit, the thumbnail will not be generated.
   */ maxThumbnailFilesize: 10,
    /**
   * If `null`, the ratio of the image will be used to calculate it.
   */ thumbnailWidth: 120,
    /**
   * The same as `thumbnailWidth`. If both are null, images will not be resized.
   */ thumbnailHeight: 120,
    /**
   * How the images should be scaled down in case both, `thumbnailWidth` and `thumbnailHeight` are provided.
   * Can be either `contain` or `crop`.
   */ thumbnailMethod: "crop",
    /**
   * If set, images will be resized to these dimensions before being **uploaded**.
   * If only one, `resizeWidth` **or** `resizeHeight` is provided, the original aspect
   * ratio of the file will be preserved.
   *
   * The `options.transformFile` function uses these options, so if the `transformFile` function
   * is overridden, these options don't do anything.
   */ resizeWidth: null,
    /**
   * See `resizeWidth`.
   */ resizeHeight: null,
    /**
   * The mime type of the resized image (before it gets uploaded to the server).
   * If `null` the original mime type will be used. To force jpeg, for example, use `image/jpeg`.
   * See `resizeWidth` for more information.
   */ resizeMimeType: null,
    /**
   * The quality of the resized images. See `resizeWidth`.
   */ resizeQuality: 0.8,
    /**
   * How the images should be scaled down in case both, `resizeWidth` and `resizeHeight` are provided.
   * Can be either `contain` or `crop`.
   */ resizeMethod: "contain",
    /**
   * The base that is used to calculate the **displayed** filesize. You can
   * change this to 1024 if you would rather display kibibytes, mebibytes,
   * etc... 1024 is technically incorrect, because `1024 bytes` are `1 kibibyte`
   * not `1 kilobyte`. You can change this to `1024` if you don't care about
   * validity.
   */ filesizeBase: 1000,
    /**
   * If not `null` defines how many files this Dropzone handles. If it exceeds,
   * the event `maxfilesexceeded` will be called. The dropzone element gets the
   * class `dz-max-files-reached` accordingly so you can provide visual
   * feedback.
   */ maxFiles: null,
    /**
   * An optional object to send additional headers to the server. Eg:
   * `{ "My-Awesome-Header": "header value" }`
   */ headers: null,
    /**
   * Should the default headers be set or not?
   * Accept: application/json <- for requesting json response
   * Cache-Control: no-cache <- Request shouldnt be cached
   * X-Requested-With: XMLHttpRequest <- We sent the request via XMLHttpRequest
   */ defaultHeaders: true,
    /**
   * If `true`, the dropzone element itself will be clickable, if `false`
   * nothing will be clickable.
   *
   * You can also pass an HTML element, a CSS selector (for multiple elements)
   * or an array of those. In that case, all of those elements will trigger an
   * upload when clicked.
   */ clickable: true,
    /**
   * Whether hidden files in directories should be ignored.
   */ ignoreHiddenFiles: true,
    /**
   * The default implementation of `accept` checks the file's mime type or
   * extension against this list. This is a comma separated list of mime
   * types or file extensions.
   *
   * Eg.: `image/*,application/pdf,.psd`
   *
   * If the Dropzone is `clickable` this option will also be used as
   * [`accept`](https://developer.mozilla.org/en-US/docs/HTML/Element/input#attr-accept)
   * parameter on the hidden file input as well.
   */ acceptedFiles: null,
    /**
   * **Deprecated!**
   * Use acceptedFiles instead.
   */ acceptedMimeTypes: null,
    /**
   * If false, files will be added to the queue but the queue will not be
   * processed automatically.
   * This can be useful if you need some additional user input before sending
   * files (or if you want want all files sent at once).
   * If you're ready to send the file simply call `myDropzone.processQueue()`.
   *
   * See the [enqueuing file uploads](#enqueuing-file-uploads) documentation
   * section for more information.
   */ autoProcessQueue: true,
    /**
   * If false, files added to the dropzone will not be queued by default.
   * You'll have to call `enqueueFile(file)` manually.
   */ autoQueue: true,
    /**
   * If `true`, this will add a link to every file preview to remove or cancel (if
   * already uploading) the file. The `dictCancelUpload`, `dictCancelUploadConfirmation`
   * and `dictRemoveFile` options are used for the wording.
   */ addRemoveLinks: false,
    /**
   * Defines where to display the file previews – if `null` the
   * Dropzone element itself is used. Can be a plain `HTMLElement` or a CSS
   * selector. The element should have the `dropzone-previews` class so
   * the previews are displayed properly.
   */ previewsContainer: null,
    /**
   * Set this to `true` if you don't want previews to be shown.
   */ disablePreviews: false,
    /**
   * This is the element the hidden input field (which is used when clicking on the
   * dropzone to trigger file selection) will be appended to. This might
   * be important in case you use frameworks to switch the content of your page.
   *
   * Can be a selector string, or an element directly.
   */ hiddenInputContainer: "body",
    /**
   * If null, no capture type will be specified
   * If camera, mobile devices will skip the file selection and choose camera
   * If microphone, mobile devices will skip the file selection and choose the microphone
   * If camcorder, mobile devices will skip the file selection and choose the camera in video mode
   * On apple devices multiple must be set to false.  AcceptedFiles may need to
   * be set to an appropriate mime type (e.g. "image/*", "audio/*", or "video/*").
   */ capture: null,
    /**
   * **Deprecated**. Use `renameFile` instead.
   */ renameFilename: null,
    /**
   * A function that is invoked before the file is uploaded to the server and renames the file.
   * This function gets the `File` as argument and can use the `file.name`. The actual name of the
   * file that gets used during the upload can be accessed through `file.upload.filename`.
   */ renameFile: null,
    /**
   * If `true` the fallback will be forced. This is very useful to test your server
   * implementations first and make sure that everything works as
   * expected without dropzone if you experience problems, and to test
   * how your fallbacks will look.
   */ forceFallback: false,
    /**
   * The text used before any files are dropped.
   */ dictDefaultMessage: "Drop files here to upload",
    /**
   * The text that replaces the default message text it the browser is not supported.
   */ dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
    /**
   * The text that will be added before the fallback form.
   * If you provide a  fallback element yourself, or if this option is `null` this will
   * be ignored.
   */ dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
    /**
   * If the filesize is too big.
   * `{{filesize}}` and `{{maxFilesize}}` will be replaced with the respective configuration values.
   */ dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
    /**
   * If the file doesn't match the file type.
   */ dictInvalidFileType: "You can't upload files of this type.",
    /**
   * If the server response was invalid.
   * `{{statusCode}}` will be replaced with the servers status code.
   */ dictResponseError: "Server responded with {{statusCode}} code.",
    /**
   * If `addRemoveLinks` is true, the text to be used for the cancel upload link.
   */ dictCancelUpload: "Cancel upload",
    /**
   * The text that is displayed if an upload was manually canceled
   */ dictUploadCanceled: "Upload canceled.",
    /**
   * If `addRemoveLinks` is true, the text to be used for confirmation when cancelling upload.
   */ dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
    /**
   * If `addRemoveLinks` is true, the text to be used to remove a file.
   */ dictRemoveFile: "Remove file",
    /**
   * If this is not null, then the user will be prompted before removing a file.
   */ dictRemoveFileConfirmation: null,
    /**
   * Displayed if `maxFiles` is st and exceeded.
   * The string `{{maxFiles}}` will be replaced by the configuration value.
   */ dictMaxFilesExceeded: "You can not upload any more files.",
    /**
   * Allows you to translate the different units. Starting with `tb` for terabytes and going down to
   * `b` for bytes.
   */ dictFileSizeUnits: {
        tb: "TB",
        gb: "GB",
        mb: "MB",
        kb: "KB",
        b: "b"
    },
    /**
   * Called when dropzone initialized
   * You can add event listeners here
   */ init () {
    },
    /**
   * Can be an **object** of additional parameters to transfer to the server, **or** a `Function`
   * that gets invoked with the `files`, `xhr` and, if it's a chunked upload, `chunk` arguments. In case
   * of a function, this needs to return a map.
   *
   * The default implementation does nothing for normal uploads, but adds relevant information for
   * chunked uploads.
   *
   * This is the same as adding hidden input fields in the form element.
   */ params (files, xhr, chunk) {
        if (chunk) return {
            dzuuid: chunk.file.upload.uuid,
            dzchunkindex: chunk.index,
            dztotalfilesize: chunk.file.size,
            dzchunksize: this.options.chunkSize,
            dztotalchunkcount: chunk.file.upload.totalChunkCount,
            dzchunkbyteoffset: chunk.index * this.options.chunkSize
        };
    },
    /**
   * A function that gets a [file](https://developer.mozilla.org/en-US/docs/DOM/File)
   * and a `done` function as parameters.
   *
   * If the done function is invoked without arguments, the file is "accepted" and will
   * be processed. If you pass an error message, the file is rejected, and the error
   * message will be displayed.
   * This function will not be called if the file is too big or doesn't match the mime types.
   */ accept (file, done) {
        return done();
    },
    /**
   * The callback that will be invoked when all chunks have been uploaded for a file.
   * It gets the file for which the chunks have been uploaded as the first parameter,
   * and the `done` function as second. `done()` needs to be invoked when everything
   * needed to finish the upload process is done.
   */ chunksUploaded: function(file, done) {
        done();
    },
    /**
   * Sends the file as binary blob in body instead of form data.
   * If this is set, the `params` option will be ignored.
   * It's an error to set this to `true` along with `uploadMultiple` since
   * multiple files cannot be in a single binary body.
   */ binaryBody: false,
    /**
   * Gets called when the browser is not supported.
   * The default implementation shows the fallback input field and adds
   * a text.
   */ fallback () {
        // This code should pass in IE7... :(
        let messageElement;
        this.element.className = `${this.element.className} dz-browser-not-supported`;
        for (let child of this.element.getElementsByTagName("div"))if (/(^| )dz-message($| )/.test(child.className)) {
            messageElement = child;
            child.className = "dz-message"; // Removes the 'dz-default' class
            break;
        }
        if (!messageElement) {
            messageElement = $3ed269f2f0fb224b$export$2e2bcd8739ae039.createElement('<div class="dz-message"><span></span></div>');
            this.element.appendChild(messageElement);
        }
        let span = messageElement.getElementsByTagName("span")[0];
        if (span) {
            if (span.textContent != null) span.textContent = this.options.dictFallbackMessage;
            else if (span.innerText != null) span.innerText = this.options.dictFallbackMessage;
        }
        return this.element.appendChild(this.getFallbackForm());
    },
    /**
   * Gets called to calculate the thumbnail dimensions.
   *
   * It gets `file`, `width` and `height` (both may be `null`) as parameters and must return an object containing:
   *
   *  - `srcWidth` & `srcHeight` (required)
   *  - `trgWidth` & `trgHeight` (required)
   *  - `srcX` & `srcY` (optional, default `0`)
   *  - `trgX` & `trgY` (optional, default `0`)
   *
   * Those values are going to be used by `ctx.drawImage()`.
   */ resize (file, width, height, resizeMethod) {
        let info = {
            srcX: 0,
            srcY: 0,
            srcWidth: file.width,
            srcHeight: file.height
        };
        let srcRatio = file.width / file.height;
        // Automatically calculate dimensions if not specified
        if (width == null && height == null) {
            width = info.srcWidth;
            height = info.srcHeight;
        } else if (width == null) width = height * srcRatio;
        else if (height == null) height = width / srcRatio;
        // Make sure images aren't upscaled
        width = Math.min(width, info.srcWidth);
        height = Math.min(height, info.srcHeight);
        let trgRatio = width / height;
        if (info.srcWidth > width || info.srcHeight > height) {
            // Image is bigger and needs rescaling
            if (resizeMethod === "crop") {
                if (srcRatio > trgRatio) {
                    info.srcHeight = file.height;
                    info.srcWidth = info.srcHeight * trgRatio;
                } else {
                    info.srcWidth = file.width;
                    info.srcHeight = info.srcWidth / trgRatio;
                }
            } else if (resizeMethod === "contain") {
                // Method 'contain'
                if (srcRatio > trgRatio) height = width / srcRatio;
                else width = height * srcRatio;
            } else throw new Error(`Unknown resizeMethod '${resizeMethod}'`);
        }
        info.srcX = (file.width - info.srcWidth) / 2;
        info.srcY = (file.height - info.srcHeight) / 2;
        info.trgWidth = width;
        info.trgHeight = height;
        return info;
    },
    /**
   * Can be used to transform the file (for example, resize an image if necessary).
   *
   * The default implementation uses `resizeWidth` and `resizeHeight` (if provided) and resizes
   * images according to those dimensions.
   *
   * Gets the `file` as the first parameter, and a `done()` function as the second, that needs
   * to be invoked with the file when the transformation is done.
   */ transformFile (file, done) {
        if ((this.options.resizeWidth || this.options.resizeHeight) && file.type.match(/image.*/)) return this.resizeImage(file, this.options.resizeWidth, this.options.resizeHeight, this.options.resizeMethod, done);
        else return done(file);
    },
    /**
   * A string that contains the template used for each dropped
   * file. Change it to fulfill your needs but make sure to properly
   * provide all elements.
   *
   * If you want to use an actual HTML element instead of providing a String
   * as a config option, you could create a div with the id `tpl`,
   * put the template inside it and provide the element like this:
   *
   *     document
   *       .querySelector('#tpl')
   *       .innerHTML
   *
   */ previewTemplate: (/*@__PURE__*/$parcel$interopDefault($fd6031f88dce2e32$exports)),
    /*
   Those functions register themselves to the events on init and handle all
   the user interface specific stuff. Overwriting them won't break the upload
   but can break the way it's displayed.
   You can overwrite them if you don't like the default behavior. If you just
   want to add an additional event handler, register it on the dropzone object
   and don't overwrite those options.
   */ // Those are self explanatory and simply concern the DragnDrop.
    drop (e) {
        return this.element.classList.remove("dz-drag-hover");
    },
    dragstart (e) {
    },
    dragend (e) {
        return this.element.classList.remove("dz-drag-hover");
    },
    dragenter (e) {
        return this.element.classList.add("dz-drag-hover");
    },
    dragover (e) {
        return this.element.classList.add("dz-drag-hover");
    },
    dragleave (e) {
        return this.element.classList.remove("dz-drag-hover");
    },
    paste (e) {
    },
    // Called whenever there are no files left in the dropzone anymore, and the
    // dropzone should be displayed as if in the initial state.
    reset () {
        return this.element.classList.remove("dz-started");
    },
    // Called when a file is added to the queue
    // Receives `file`
    addedfile (file) {
        if (this.element === this.previewsContainer) this.element.classList.add("dz-started");
        if (this.previewsContainer && !this.options.disablePreviews) {
            file.previewElement = $3ed269f2f0fb224b$export$2e2bcd8739ae039.createElement(this.options.previewTemplate.trim());
            file.previewTemplate = file.previewElement; // Backwards compatibility
            this.previewsContainer.appendChild(file.previewElement);
            for (var node of file.previewElement.querySelectorAll("[data-dz-name]"))node.textContent = file.name;
            for (node of file.previewElement.querySelectorAll("[data-dz-size]"))node.innerHTML = this.filesize(file.size);
            if (this.options.addRemoveLinks) {
                file._removeLink = $3ed269f2f0fb224b$export$2e2bcd8739ae039.createElement(`<a class="dz-remove" href="javascript:undefined;" data-dz-remove>${this.options.dictRemoveFile}</a>`);
                file.previewElement.appendChild(file._removeLink);
            }
            let removeFileEvent = (e)=>{
                e.preventDefault();
                e.stopPropagation();
                if (file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING) return $3ed269f2f0fb224b$export$2e2bcd8739ae039.confirm(this.options.dictCancelUploadConfirmation, ()=>this.removeFile(file)
                );
                else {
                    if (this.options.dictRemoveFileConfirmation) return $3ed269f2f0fb224b$export$2e2bcd8739ae039.confirm(this.options.dictRemoveFileConfirmation, ()=>this.removeFile(file)
                    );
                    else return this.removeFile(file);
                }
            };
            for (let removeLink of file.previewElement.querySelectorAll("[data-dz-remove]"))removeLink.addEventListener("click", removeFileEvent);
        }
    },
    // Called whenever a file is removed.
    removedfile (file) {
        if (file.previewElement != null && file.previewElement.parentNode != null) file.previewElement.parentNode.removeChild(file.previewElement);
        return this._updateMaxFilesReachedClass();
    },
    // Called when a thumbnail has been generated
    // Receives `file` and `dataUrl`
    thumbnail (file, dataUrl) {
        if (file.previewElement) {
            file.previewElement.classList.remove("dz-file-preview");
            for (let thumbnailElement of file.previewElement.querySelectorAll("[data-dz-thumbnail]")){
                thumbnailElement.alt = file.name;
                thumbnailElement.src = dataUrl;
            }
            return setTimeout(()=>file.previewElement.classList.add("dz-image-preview")
            , 1);
        }
    },
    // Called whenever an error occurs
    // Receives `file` and `message`
    error (file, message) {
        if (file.previewElement) {
            file.previewElement.classList.add("dz-error");
            if (typeof message !== "string" && message.error) message = message.error;
            for (let node of file.previewElement.querySelectorAll("[data-dz-errormessage]"))node.textContent = message;
        }
    },
    errormultiple () {
    },
    // Called when a file gets processed. Since there is a cue, not all added
    // files are processed immediately.
    // Receives `file`
    processing (file) {
        if (file.previewElement) {
            file.previewElement.classList.add("dz-processing");
            if (file._removeLink) return file._removeLink.innerHTML = this.options.dictCancelUpload;
        }
    },
    processingmultiple () {
    },
    // Called whenever the upload progress gets updated.
    // Receives `file`, `progress` (percentage 0-100) and `bytesSent`.
    // To get the total number of bytes of the file, use `file.size`
    uploadprogress (file, progress, bytesSent) {
        if (file.previewElement) for (let node of file.previewElement.querySelectorAll("[data-dz-uploadprogress]"))node.nodeName === "PROGRESS" ? node.value = progress : node.style.width = `${progress}%`;
    },
    // Called whenever the total upload progress gets updated.
    // Called with totalUploadProgress (0-100), totalBytes and totalBytesSent
    totaluploadprogress () {
    },
    // Called just before the file is sent. Gets the `xhr` object as second
    // parameter, so you can modify it (for example to add a CSRF token) and a
    // `formData` object to add additional information.
    sending () {
    },
    sendingmultiple () {
    },
    // When the complete upload is finished and successful
    // Receives `file`
    success (file) {
        if (file.previewElement) return file.previewElement.classList.add("dz-success");
    },
    successmultiple () {
    },
    // When the upload is canceled.
    canceled (file) {
        return this.emit("error", file, this.options.dictUploadCanceled);
    },
    canceledmultiple () {
    },
    // When the upload is finished, either with success or an error.
    // Receives `file`
    complete (file) {
        if (file._removeLink) file._removeLink.innerHTML = this.options.dictRemoveFile;
        if (file.previewElement) return file.previewElement.classList.add("dz-complete");
    },
    completemultiple () {
    },
    maxfilesexceeded () {
    },
    maxfilesreached () {
    },
    queuecomplete () {
    },
    addedfiles () {
    }
};
var $4ca367182776f80b$export$2e2bcd8739ae039 = $4ca367182776f80b$var$defaultOptions;


class $3ed269f2f0fb224b$export$2e2bcd8739ae039 extends $4040acfd8584338d$export$2e2bcd8739ae039 {
    static initClass() {
        // Exposing the emitter class, mainly for tests
        this.prototype.Emitter = $4040acfd8584338d$export$2e2bcd8739ae039;
        /*
     This is a list of all available events you can register on a dropzone object.

     You can register an event handler like this:

     dropzone.on("dragEnter", function() { });

     */ this.prototype.events = [
            "drop",
            "dragstart",
            "dragend",
            "dragenter",
            "dragover",
            "dragleave",
            "addedfile",
            "addedfiles",
            "removedfile",
            "thumbnail",
            "error",
            "errormultiple",
            "processing",
            "processingmultiple",
            "uploadprogress",
            "totaluploadprogress",
            "sending",
            "sendingmultiple",
            "success",
            "successmultiple",
            "canceled",
            "canceledmultiple",
            "complete",
            "completemultiple",
            "reset",
            "maxfilesexceeded",
            "maxfilesreached",
            "queuecomplete", 
        ];
        this.prototype._thumbnailQueue = [];
        this.prototype._processingThumbnail = false;
    }
    // Returns all files that have been accepted
    getAcceptedFiles() {
        return this.files.filter((file)=>file.accepted
        ).map((file)=>file
        );
    }
    // Returns all files that have been rejected
    // Not sure when that's going to be useful, but added for completeness.
    getRejectedFiles() {
        return this.files.filter((file)=>!file.accepted
        ).map((file)=>file
        );
    }
    getFilesWithStatus(status) {
        return this.files.filter((file)=>file.status === status
        ).map((file)=>file
        );
    }
    // Returns all files that are in the queue
    getQueuedFiles() {
        return this.getFilesWithStatus($3ed269f2f0fb224b$export$2e2bcd8739ae039.QUEUED);
    }
    getUploadingFiles() {
        return this.getFilesWithStatus($3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING);
    }
    getAddedFiles() {
        return this.getFilesWithStatus($3ed269f2f0fb224b$export$2e2bcd8739ae039.ADDED);
    }
    // Files that are either queued or uploading
    getActiveFiles() {
        return this.files.filter((file)=>file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING || file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.QUEUED
        ).map((file)=>file
        );
    }
    // The function that gets called when Dropzone is initialized. You
    // can (and should) setup event listeners inside this function.
    init() {
        // In case it isn't set already
        if (this.element.tagName === "form") this.element.setAttribute("enctype", "multipart/form-data");
        if (this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message")) this.element.appendChild($3ed269f2f0fb224b$export$2e2bcd8739ae039.createElement(`<div class="dz-default dz-message"><button class="dz-button" type="button">${this.options.dictDefaultMessage}</button></div>`));
        if (this.clickableElements.length) {
            let setupHiddenFileInput = ()=>{
                if (this.hiddenFileInput) this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput);
                this.hiddenFileInput = document.createElement("input");
                this.hiddenFileInput.setAttribute("type", "file");
                if (this.options.maxFiles === null || this.options.maxFiles > 1) this.hiddenFileInput.setAttribute("multiple", "multiple");
                this.hiddenFileInput.className = "dz-hidden-input";
                if (this.options.acceptedFiles !== null) this.hiddenFileInput.setAttribute("accept", this.options.acceptedFiles);
                if (this.options.capture !== null) this.hiddenFileInput.setAttribute("capture", this.options.capture);
                // Making sure that no one can "tab" into this field.
                this.hiddenFileInput.setAttribute("tabindex", "-1");
                // Not setting `display="none"` because some browsers don't accept clicks
                // on elements that aren't displayed.
                this.hiddenFileInput.style.visibility = "hidden";
                this.hiddenFileInput.style.position = "absolute";
                this.hiddenFileInput.style.top = "0";
                this.hiddenFileInput.style.left = "0";
                this.hiddenFileInput.style.height = "0";
                this.hiddenFileInput.style.width = "0";
                $3ed269f2f0fb224b$export$2e2bcd8739ae039.getElement(this.options.hiddenInputContainer, "hiddenInputContainer").appendChild(this.hiddenFileInput);
                this.hiddenFileInput.addEventListener("change", ()=>{
                    let { files: files  } = this.hiddenFileInput;
                    if (files.length) for (let file of files)this.addFile(file);
                    this.emit("addedfiles", files);
                    setupHiddenFileInput();
                });
            };
            setupHiddenFileInput();
        }
        this.URL = window.URL !== null ? window.URL : window.webkitURL;
        // Setup all event listeners on the Dropzone object itself.
        // They're not in @setupEventListeners() because they shouldn't be removed
        // again when the dropzone gets disabled.
        for (let eventName of this.events)this.on(eventName, this.options[eventName]);
        this.on("uploadprogress", ()=>this.updateTotalUploadProgress()
        );
        this.on("removedfile", ()=>this.updateTotalUploadProgress()
        );
        this.on("canceled", (file)=>this.emit("complete", file)
        );
        // Emit a `queuecomplete` event if all files finished uploading.
        this.on("complete", (file)=>{
            if (this.getAddedFiles().length === 0 && this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) // This needs to be deferred so that `queuecomplete` really triggers after `complete`
            return setTimeout(()=>this.emit("queuecomplete")
            , 0);
        });
        const containsFiles = function(e) {
            if (e.dataTransfer.types) // Because e.dataTransfer.types is an Object in
            // IE, we need to iterate like this instead of
            // using e.dataTransfer.types.some()
            for(var i = 0; i < e.dataTransfer.types.length; i++){
                if (e.dataTransfer.types[i] === "Files") return true;
            }
            return false;
        };
        let noPropagation = function(e) {
            // If there are no files, we don't want to stop
            // propagation so we don't interfere with other
            // drag and drop behaviour.
            if (!containsFiles(e)) return;
            e.stopPropagation();
            if (e.preventDefault) return e.preventDefault();
            else return e.returnValue = false;
        };
        // Create the listeners
        this.listeners = [
            {
                element: this.element,
                events: {
                    dragstart: (e)=>{
                        return this.emit("dragstart", e);
                    },
                    dragenter: (e)=>{
                        noPropagation(e);
                        return this.emit("dragenter", e);
                    },
                    dragover: (e)=>{
                        // Makes it possible to drag files from chrome's download bar
                        // http://stackoverflow.com/questions/19526430/drag-and-drop-file-uploads-from-chrome-downloads-bar
                        // Try is required to prevent bug in Internet Explorer 11 (SCRIPT65535 exception)
                        let efct;
                        try {
                            efct = e.dataTransfer.effectAllowed;
                        } catch (error) {
                        }
                        e.dataTransfer.dropEffect = "move" === efct || "linkMove" === efct ? "move" : "copy";
                        noPropagation(e);
                        return this.emit("dragover", e);
                    },
                    dragleave: (e)=>{
                        return this.emit("dragleave", e);
                    },
                    drop: (e)=>{
                        noPropagation(e);
                        return this.drop(e);
                    },
                    dragend: (e)=>{
                        return this.emit("dragend", e);
                    }
                }
            }, 
        ];
        this.clickableElements.forEach((clickableElement)=>{
            return this.listeners.push({
                element: clickableElement,
                events: {
                    click: (evt)=>{
                        // Only the actual dropzone or the message element should trigger file selection
                        if (clickableElement !== this.element || evt.target === this.element || $3ed269f2f0fb224b$export$2e2bcd8739ae039.elementInside(evt.target, this.element.querySelector(".dz-message"))) this.hiddenFileInput.click(); // Forward the click
                        return true;
                    }
                }
            });
        });
        this.enable();
        return this.options.init.call(this);
    }
    // Not fully tested yet
    destroy() {
        this.disable();
        this.removeAllFiles(true);
        if (this.hiddenFileInput != null ? this.hiddenFileInput.parentNode : undefined) {
            this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput);
            this.hiddenFileInput = null;
        }
        delete this.element.dropzone;
        return $3ed269f2f0fb224b$export$2e2bcd8739ae039.instances.splice($3ed269f2f0fb224b$export$2e2bcd8739ae039.instances.indexOf(this), 1);
    }
    updateTotalUploadProgress() {
        let totalUploadProgress;
        let totalBytesSent = 0;
        let totalBytes = 0;
        let activeFiles = this.getActiveFiles();
        if (activeFiles.length) {
            for (let file of this.getActiveFiles()){
                totalBytesSent += file.upload.bytesSent;
                totalBytes += file.upload.total;
            }
            totalUploadProgress = 100 * totalBytesSent / totalBytes;
        } else totalUploadProgress = 100;
        return this.emit("totaluploadprogress", totalUploadProgress, totalBytes, totalBytesSent);
    }
    // @options.paramName can be a function taking one parameter rather than a string.
    // A parameter name for a file is obtained simply by calling this with an index number.
    _getParamName(n) {
        if (typeof this.options.paramName === "function") return this.options.paramName(n);
        else return `${this.options.paramName}${this.options.uploadMultiple ? `[${n}]` : ""}`;
    }
    // If @options.renameFile is a function,
    // the function will be used to rename the file.name before appending it to the formData
    _renameFile(file) {
        if (typeof this.options.renameFile !== "function") return file.name;
        return this.options.renameFile(file);
    }
    // Returns a form that can be used as fallback if the browser does not support DragnDrop
    //
    // If the dropzone is already a form, only the input field and button are returned. Otherwise a complete form element is provided.
    // This code has to pass in IE7 :(
    getFallbackForm() {
        let existingFallback, form;
        if (existingFallback = this.getExistingFallback()) return existingFallback;
        let fieldsString = '<div class="dz-fallback">';
        if (this.options.dictFallbackText) fieldsString += `<p>${this.options.dictFallbackText}</p>`;
        fieldsString += `<input type="file" name="${this._getParamName(0)}" ${this.options.uploadMultiple ? 'multiple="multiple"' : undefined} /><input type="submit" value="Upload!"></div>`;
        let fields = $3ed269f2f0fb224b$export$2e2bcd8739ae039.createElement(fieldsString);
        if (this.element.tagName !== "FORM") {
            form = $3ed269f2f0fb224b$export$2e2bcd8739ae039.createElement(`<form action="${this.options.url}" enctype="multipart/form-data" method="${this.options.method}"></form>`);
            form.appendChild(fields);
        } else {
            // Make sure that the enctype and method attributes are set properly
            this.element.setAttribute("enctype", "multipart/form-data");
            this.element.setAttribute("method", this.options.method);
        }
        return form != null ? form : fields;
    }
    // Returns the fallback elements if they exist already
    //
    // This code has to pass in IE7 :(
    getExistingFallback() {
        let getFallback = function(elements) {
            for (let el of elements){
                if (/(^| )fallback($| )/.test(el.className)) return el;
            }
        };
        for (let tagName of [
            "div",
            "form"
        ]){
            var fallback;
            if (fallback = getFallback(this.element.getElementsByTagName(tagName))) return fallback;
        }
    }
    // Activates all listeners stored in @listeners
    setupEventListeners() {
        return this.listeners.map((elementListeners)=>(()=>{
                let result = [];
                for(let event in elementListeners.events){
                    let listener = elementListeners.events[event];
                    result.push(elementListeners.element.addEventListener(event, listener, false));
                }
                return result;
            })()
        );
    }
    // Deactivates all listeners stored in @listeners
    removeEventListeners() {
        return this.listeners.map((elementListeners)=>(()=>{
                let result = [];
                for(let event in elementListeners.events){
                    let listener = elementListeners.events[event];
                    result.push(elementListeners.element.removeEventListener(event, listener, false));
                }
                return result;
            })()
        );
    }
    // Removes all event listeners and cancels all files in the queue or being processed.
    disable() {
        this.clickableElements.forEach((element)=>element.classList.remove("dz-clickable")
        );
        this.removeEventListeners();
        this.disabled = true;
        return this.files.map((file)=>this.cancelUpload(file)
        );
    }
    enable() {
        delete this.disabled;
        this.clickableElements.forEach((element)=>element.classList.add("dz-clickable")
        );
        return this.setupEventListeners();
    }
    // Returns a nicely formatted filesize
    filesize(size) {
        let selectedSize = 0;
        let selectedUnit = "b";
        if (size > 0) {
            let units = [
                "tb",
                "gb",
                "mb",
                "kb",
                "b"
            ];
            for(let i = 0; i < units.length; i++){
                let unit = units[i];
                let cutoff = Math.pow(this.options.filesizeBase, 4 - i) / 10;
                if (size >= cutoff) {
                    selectedSize = size / Math.pow(this.options.filesizeBase, 4 - i);
                    selectedUnit = unit;
                    break;
                }
            }
            selectedSize = Math.round(10 * selectedSize) / 10; // Cutting of digits
        }
        return `<strong>${selectedSize}</strong> ${this.options.dictFileSizeUnits[selectedUnit]}`;
    }
    // Adds or removes the `dz-max-files-reached` class from the form.
    _updateMaxFilesReachedClass() {
        if (this.options.maxFiles != null && this.getAcceptedFiles().length >= this.options.maxFiles) {
            if (this.getAcceptedFiles().length === this.options.maxFiles) this.emit("maxfilesreached", this.files);
            return this.element.classList.add("dz-max-files-reached");
        } else return this.element.classList.remove("dz-max-files-reached");
    }
    drop(e) {
        if (!e.dataTransfer) return;
        this.emit("drop", e);
        // Convert the FileList to an Array
        // This is necessary for IE11
        let files = [];
        for(let i = 0; i < e.dataTransfer.files.length; i++)files[i] = e.dataTransfer.files[i];
        // Even if it's a folder, files.length will contain the folders.
        if (files.length) {
            let { items: items  } = e.dataTransfer;
            if (items && items.length && items[0].webkitGetAsEntry != null) // The browser supports dropping of folders, so handle items instead of files
            this._addFilesFromItems(items);
            else this.handleFiles(files);
        }
        this.emit("addedfiles", files);
    }
    paste(e) {
        if ($3ed269f2f0fb224b$var$__guard__(e != null ? e.clipboardData : undefined, (x)=>x.items
        ) == null) return;
        this.emit("paste", e);
        let { items: items  } = e.clipboardData;
        if (items.length) return this._addFilesFromItems(items);
    }
    handleFiles(files) {
        for (let file of files)this.addFile(file);
    }
    // When a folder is dropped (or files are pasted), items must be handled
    // instead of files.
    _addFilesFromItems(items) {
        return (()=>{
            let result = [];
            for (let item of items){
                var entry;
                if (item.webkitGetAsEntry != null && (entry = item.webkitGetAsEntry())) {
                    if (entry.isFile) result.push(this.addFile(item.getAsFile()));
                    else if (entry.isDirectory) // Append all files from that directory to files
                    result.push(this._addFilesFromDirectory(entry, entry.name));
                    else result.push(undefined);
                } else if (item.getAsFile != null) {
                    if (item.kind == null || item.kind === "file") result.push(this.addFile(item.getAsFile()));
                    else result.push(undefined);
                } else result.push(undefined);
            }
            return result;
        })();
    }
    // Goes through the directory, and adds each file it finds recursively
    _addFilesFromDirectory(directory, path) {
        let dirReader = directory.createReader();
        let errorHandler = (error)=>$3ed269f2f0fb224b$var$__guardMethod__(console, "log", (o)=>o.log(error)
            )
        ;
        var readEntries = ()=>{
            return dirReader.readEntries((entries)=>{
                if (entries.length > 0) {
                    for (let entry of entries){
                        if (entry.isFile) entry.file((file)=>{
                            if (this.options.ignoreHiddenFiles && file.name.substring(0, 1) === ".") return;
                            file.fullPath = `${path}/${file.name}`;
                            return this.addFile(file);
                        });
                        else if (entry.isDirectory) this._addFilesFromDirectory(entry, `${path}/${entry.name}`);
                    }
                    // Recursively call readEntries() again, since browser only handle
                    // the first 100 entries.
                    // See: https://developer.mozilla.org/en-US/docs/Web/API/DirectoryReader#readEntries
                    readEntries();
                }
                return null;
            }, errorHandler);
        };
        return readEntries();
    }
    // If `done()` is called without argument the file is accepted
    // If you call it with an error message, the file is rejected
    // (This allows for asynchronous validation)
    //
    // This function checks the filesize, and if the file.type passes the
    // `acceptedFiles` check.
    accept(file, done) {
        if (this.options.maxFilesize && file.size > this.options.maxFilesize * 1048576) done(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(file.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize));
        else if (!$3ed269f2f0fb224b$export$2e2bcd8739ae039.isValidFile(file, this.options.acceptedFiles)) done(this.options.dictInvalidFileType);
        else if (this.options.maxFiles != null && this.getAcceptedFiles().length >= this.options.maxFiles) {
            done(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles));
            this.emit("maxfilesexceeded", file);
        } else this.options.accept.call(this, file, done);
    }
    addFile(file) {
        file.upload = {
            uuid: $3ed269f2f0fb224b$export$2e2bcd8739ae039.uuidv4(),
            progress: 0,
            // Setting the total upload size to file.size for the beginning
            // It's actual different than the size to be transmitted.
            total: file.size,
            bytesSent: 0,
            filename: this._renameFile(file)
        };
        this.files.push(file);
        file.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.ADDED;
        this.emit("addedfile", file);
        this._enqueueThumbnail(file);
        this.accept(file, (error)=>{
            if (error) {
                file.accepted = false;
                this._errorProcessing([
                    file
                ], error); // Will set the file.status
            } else {
                file.accepted = true;
                if (this.options.autoQueue) this.enqueueFile(file);
                 // Will set .accepted = true
            }
            this._updateMaxFilesReachedClass();
        });
    }
    // Wrapper for enqueueFile
    enqueueFiles(files) {
        for (let file of files)this.enqueueFile(file);
        return null;
    }
    enqueueFile(file) {
        if (file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.ADDED && file.accepted === true) {
            file.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.QUEUED;
            if (this.options.autoProcessQueue) return setTimeout(()=>this.processQueue()
            , 0); // Deferring the call
        } else throw new Error("This file can't be queued because it has already been processed or was rejected.");
    }
    _enqueueThumbnail(file) {
        if (this.options.createImageThumbnails && file.type.match(/image.*/) && file.size <= this.options.maxThumbnailFilesize * 1048576) {
            this._thumbnailQueue.push(file);
            return setTimeout(()=>this._processThumbnailQueue()
            , 0); // Deferring the call
        }
    }
    _processThumbnailQueue() {
        if (this._processingThumbnail || this._thumbnailQueue.length === 0) return;
        this._processingThumbnail = true;
        let file = this._thumbnailQueue.shift();
        return this.createThumbnail(file, this.options.thumbnailWidth, this.options.thumbnailHeight, this.options.thumbnailMethod, true, (dataUrl)=>{
            this.emit("thumbnail", file, dataUrl);
            this._processingThumbnail = false;
            return this._processThumbnailQueue();
        });
    }
    // Can be called by the user to remove a file
    removeFile(file) {
        if (file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING) this.cancelUpload(file);
        this.files = $3ed269f2f0fb224b$var$without(this.files, file);
        this.emit("removedfile", file);
        if (this.files.length === 0) return this.emit("reset");
    }
    // Removes all files that aren't currently processed from the list
    removeAllFiles(cancelIfNecessary) {
        // Create a copy of files since removeFile() changes the @files array.
        if (cancelIfNecessary == null) cancelIfNecessary = false;
        for (let file of this.files.slice())if (file.status !== $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING || cancelIfNecessary) this.removeFile(file);
        return null;
    }
    // Resizes an image before it gets sent to the server. This function is the default behavior of
    // `options.transformFile` if `resizeWidth` or `resizeHeight` are set. The callback is invoked with
    // the resized blob.
    resizeImage(file, width, height, resizeMethod, callback) {
        return this.createThumbnail(file, width, height, resizeMethod, true, (dataUrl, canvas)=>{
            if (canvas == null) // The image has not been resized
            return callback(file);
            else {
                let { resizeMimeType: resizeMimeType  } = this.options;
                if (resizeMimeType == null) resizeMimeType = file.type;
                let resizedDataURL = canvas.toDataURL(resizeMimeType, this.options.resizeQuality);
                if (resizeMimeType === "image/jpeg" || resizeMimeType === "image/jpg") // Now add the original EXIF information
                resizedDataURL = $3ed269f2f0fb224b$var$ExifRestore.restore(file.dataURL, resizedDataURL);
                return callback($3ed269f2f0fb224b$export$2e2bcd8739ae039.dataURItoBlob(resizedDataURL));
            }
        });
    }
    createThumbnail(file, width, height, resizeMethod, fixOrientation, callback) {
        let fileReader = new FileReader();
        fileReader.onload = ()=>{
            file.dataURL = fileReader.result;
            // Don't bother creating a thumbnail for SVG images since they're vector
            if (file.type === "image/svg+xml") {
                if (callback != null) callback(fileReader.result);
                return;
            }
            this.createThumbnailFromUrl(file, width, height, resizeMethod, fixOrientation, callback);
        };
        fileReader.readAsDataURL(file);
    }
    // `mockFile` needs to have these attributes:
    //
    //     { name: 'name', size: 12345, imageUrl: '' }
    //
    // `callback` will be invoked when the image has been downloaded and displayed.
    // `crossOrigin` will be added to the `img` tag when accessing the file.
    displayExistingFile(mockFile, imageUrl, callback, crossOrigin, resizeThumbnail = true) {
        this.emit("addedfile", mockFile);
        this.emit("complete", mockFile);
        if (!resizeThumbnail) {
            this.emit("thumbnail", mockFile, imageUrl);
            if (callback) callback();
        } else {
            let onDone = (thumbnail)=>{
                this.emit("thumbnail", mockFile, thumbnail);
                if (callback) callback();
            };
            mockFile.dataURL = imageUrl;
            this.createThumbnailFromUrl(mockFile, this.options.thumbnailWidth, this.options.thumbnailHeight, this.options.thumbnailMethod, this.options.fixOrientation, onDone, crossOrigin);
        }
    }
    createThumbnailFromUrl(file, width, height, resizeMethod, fixOrientation, callback, crossOrigin) {
        // Not using `new Image` here because of a bug in latest Chrome versions.
        // See https://github.com/enyo/dropzone/pull/226
        let img = document.createElement("img");
        if (crossOrigin) img.crossOrigin = crossOrigin;
        // fixOrientation is not needed anymore with browsers handling imageOrientation
        fixOrientation = getComputedStyle(document.body)["imageOrientation"] == "from-image" ? false : fixOrientation;
        img.onload = ()=>{
            let loadExif = (callback)=>callback(1)
            ;
            if (typeof EXIF !== "undefined" && EXIF !== null && fixOrientation) loadExif = (callback)=>EXIF.getData(img, function() {
                    return callback(EXIF.getTag(this, "Orientation"));
                })
            ;
            return loadExif((orientation)=>{
                file.width = img.width;
                file.height = img.height;
                let resizeInfo = this.options.resize.call(this, file, width, height, resizeMethod);
                let canvas = document.createElement("canvas");
                let ctx = canvas.getContext("2d");
                canvas.width = resizeInfo.trgWidth;
                canvas.height = resizeInfo.trgHeight;
                if (orientation > 4) {
                    canvas.width = resizeInfo.trgHeight;
                    canvas.height = resizeInfo.trgWidth;
                }
                switch(orientation){
                    case 2:
                        // horizontal flip
                        ctx.translate(canvas.width, 0);
                        ctx.scale(-1, 1);
                        break;
                    case 3:
                        // 180° rotate left
                        ctx.translate(canvas.width, canvas.height);
                        ctx.rotate(Math.PI);
                        break;
                    case 4:
                        // vertical flip
                        ctx.translate(0, canvas.height);
                        ctx.scale(1, -1);
                        break;
                    case 5:
                        // vertical flip + 90 rotate right
                        ctx.rotate(0.5 * Math.PI);
                        ctx.scale(1, -1);
                        break;
                    case 6:
                        // 90° rotate right
                        ctx.rotate(0.5 * Math.PI);
                        ctx.translate(0, -canvas.width);
                        break;
                    case 7:
                        // horizontal flip + 90 rotate right
                        ctx.rotate(0.5 * Math.PI);
                        ctx.translate(canvas.height, -canvas.width);
                        ctx.scale(-1, 1);
                        break;
                    case 8:
                        // 90° rotate left
                        ctx.rotate(-0.5 * Math.PI);
                        ctx.translate(-canvas.height, 0);
                        break;
                }
                // This is a bugfix for iOS' scaling bug.
                $3ed269f2f0fb224b$var$drawImageIOSFix(ctx, img, resizeInfo.srcX != null ? resizeInfo.srcX : 0, resizeInfo.srcY != null ? resizeInfo.srcY : 0, resizeInfo.srcWidth, resizeInfo.srcHeight, resizeInfo.trgX != null ? resizeInfo.trgX : 0, resizeInfo.trgY != null ? resizeInfo.trgY : 0, resizeInfo.trgWidth, resizeInfo.trgHeight);
                let thumbnail = canvas.toDataURL("image/png");
                if (callback != null) return callback(thumbnail, canvas);
            });
        };
        if (callback != null) img.onerror = callback;
        return img.src = file.dataURL;
    }
    // Goes through the queue and processes files if there aren't too many already.
    processQueue() {
        let { parallelUploads: parallelUploads  } = this.options;
        let processingLength = this.getUploadingFiles().length;
        let i = processingLength;
        // There are already at least as many files uploading than should be
        if (processingLength >= parallelUploads) return;
        let queuedFiles = this.getQueuedFiles();
        if (!(queuedFiles.length > 0)) return;
        if (this.options.uploadMultiple) // The files should be uploaded in one request
        return this.processFiles(queuedFiles.slice(0, parallelUploads - processingLength));
        else while(i < parallelUploads){
            if (!queuedFiles.length) return;
             // Nothing left to process
            this.processFile(queuedFiles.shift());
            i++;
        }
    }
    // Wrapper for `processFiles`
    processFile(file) {
        return this.processFiles([
            file
        ]);
    }
    // Loads the file, then calls finishedLoading()
    processFiles(files) {
        for (let file of files){
            file.processing = true; // Backwards compatibility
            file.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING;
            this.emit("processing", file);
        }
        if (this.options.uploadMultiple) this.emit("processingmultiple", files);
        return this.uploadFiles(files);
    }
    _getFilesWithXhr(xhr) {
        let files;
        return files = this.files.filter((file)=>file.xhr === xhr
        ).map((file)=>file
        );
    }
    // Cancels the file upload and sets the status to CANCELED
    // **if** the file is actually being uploaded.
    // If it's still in the queue, the file is being removed from it and the status
    // set to CANCELED.
    cancelUpload(file) {
        if (file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING) {
            let groupedFiles = this._getFilesWithXhr(file.xhr);
            for (let groupedFile of groupedFiles)groupedFile.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.CANCELED;
            if (typeof file.xhr !== "undefined") file.xhr.abort();
            for (let groupedFile1 of groupedFiles)this.emit("canceled", groupedFile1);
            if (this.options.uploadMultiple) this.emit("canceledmultiple", groupedFiles);
        } else if (file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.ADDED || file.status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.QUEUED) {
            file.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.CANCELED;
            this.emit("canceled", file);
            if (this.options.uploadMultiple) this.emit("canceledmultiple", [
                file
            ]);
        }
        if (this.options.autoProcessQueue) return this.processQueue();
    }
    resolveOption(option, ...args) {
        if (typeof option === "function") return option.apply(this, args);
        return option;
    }
    uploadFile(file) {
        return this.uploadFiles([
            file
        ]);
    }
    uploadFiles(files) {
        this._transformFiles(files, (transformedFiles)=>{
            if (this.options.chunking) {
                // Chunking is not allowed to be used with `uploadMultiple` so we know
                // that there is only __one__file.
                let transformedFile = transformedFiles[0];
                files[0].upload.chunked = this.options.chunking && (this.options.forceChunking || transformedFile.size > this.options.chunkSize);
                files[0].upload.totalChunkCount = Math.ceil(transformedFile.size / this.options.chunkSize);
            }
            if (files[0].upload.chunked) {
                // This file should be sent in chunks!
                // If the chunking option is set, we **know** that there can only be **one** file, since
                // uploadMultiple is not allowed with this option.
                let file = files[0];
                let transformedFile = transformedFiles[0];
                let startedChunkCount = 0;
                file.upload.chunks = [];
                let handleNextChunk = ()=>{
                    let chunkIndex = 0;
                    // Find the next item in file.upload.chunks that is not defined yet.
                    while(file.upload.chunks[chunkIndex] !== undefined)chunkIndex++;
                    // This means, that all chunks have already been started.
                    if (chunkIndex >= file.upload.totalChunkCount) return;
                    startedChunkCount++;
                    let start = chunkIndex * this.options.chunkSize;
                    let end = Math.min(start + this.options.chunkSize, transformedFile.size);
                    let dataBlock = {
                        name: this._getParamName(0),
                        data: transformedFile.webkitSlice ? transformedFile.webkitSlice(start, end) : transformedFile.slice(start, end),
                        filename: file.upload.filename,
                        chunkIndex: chunkIndex
                    };
                    file.upload.chunks[chunkIndex] = {
                        file: file,
                        index: chunkIndex,
                        dataBlock: dataBlock,
                        status: $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING,
                        progress: 0,
                        retries: 0
                    };
                    this._uploadData(files, [
                        dataBlock
                    ]);
                };
                file.upload.finishedChunkUpload = (chunk, response)=>{
                    let allFinished = true;
                    chunk.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.SUCCESS;
                    // Clear the data from the chunk
                    chunk.dataBlock = null;
                    chunk.response = chunk.xhr.responseText;
                    chunk.responseHeaders = chunk.xhr.getAllResponseHeaders();
                    // Leaving this reference to xhr will cause memory leaks.
                    chunk.xhr = null;
                    for(let i = 0; i < file.upload.totalChunkCount; i++){
                        if (file.upload.chunks[i] === undefined) return handleNextChunk();
                        if (file.upload.chunks[i].status !== $3ed269f2f0fb224b$export$2e2bcd8739ae039.SUCCESS) allFinished = false;
                    }
                    if (allFinished) this.options.chunksUploaded(file, ()=>{
                        this._finished(files, response, null);
                    });
                };
                if (this.options.parallelChunkUploads) for(let i = 0; i < file.upload.totalChunkCount; i++)handleNextChunk();
                else handleNextChunk();
            } else {
                let dataBlocks = [];
                for(let i = 0; i < files.length; i++)dataBlocks[i] = {
                    name: this._getParamName(i),
                    data: transformedFiles[i],
                    filename: files[i].upload.filename
                };
                this._uploadData(files, dataBlocks);
            }
        });
    }
    /// Returns the right chunk for given file and xhr
    _getChunk(file, xhr) {
        for(let i = 0; i < file.upload.totalChunkCount; i++){
            if (file.upload.chunks[i] !== undefined && file.upload.chunks[i].xhr === xhr) return file.upload.chunks[i];
        }
    }
    // This function actually uploads the file(s) to the server.
    //
    //  If dataBlocks contains the actual data to upload (meaning, that this could
    // either be transformed files, or individual chunks for chunked upload) then
    // they will be used for the actual data to upload.
    _uploadData(files, dataBlocks) {
        let xhr = new XMLHttpRequest();
        // Put the xhr object in the file objects to be able to reference it later.
        for (let file of files)file.xhr = xhr;
        if (files[0].upload.chunked) // Put the xhr object in the right chunk object, so it can be associated
        // later, and found with _getChunk.
        files[0].upload.chunks[dataBlocks[0].chunkIndex].xhr = xhr;
        let method = this.resolveOption(this.options.method, files, dataBlocks);
        let url = this.resolveOption(this.options.url, files, dataBlocks);
        xhr.open(method, url, true);
        // Setting the timeout after open because of IE11 issue: https://gitlab.com/meno/dropzone/issues/8
        let timeout = this.resolveOption(this.options.timeout, files);
        if (timeout) xhr.timeout = this.resolveOption(this.options.timeout, files);
        // Has to be after `.open()`. See https://github.com/enyo/dropzone/issues/179
        xhr.withCredentials = !!this.options.withCredentials;
        xhr.onload = (e)=>{
            this._finishedUploading(files, xhr, e);
        };
        xhr.ontimeout = ()=>{
            this._handleUploadError(files, xhr, `Request timedout after ${this.options.timeout / 1000} seconds`);
        };
        xhr.onerror = ()=>{
            this._handleUploadError(files, xhr);
        };
        // Some browsers do not have the .upload property
        let progressObj = xhr.upload != null ? xhr.upload : xhr;
        progressObj.onprogress = (e)=>this._updateFilesUploadProgress(files, xhr, e)
        ;
        let headers = this.options.defaultHeaders ? {
            Accept: "application/json",
            "Cache-Control": "no-cache",
            "X-Requested-With": "XMLHttpRequest"
        } : {
        };
        if (this.options.binaryBody) headers["Content-Type"] = files[0].type;
        if (this.options.headers) (0,just_extend__WEBPACK_IMPORTED_MODULE_0__["default"])(headers, this.options.headers);
        for(let headerName in headers){
            let headerValue = headers[headerName];
            if (headerValue) xhr.setRequestHeader(headerName, headerValue);
        }
        if (this.options.binaryBody) {
            // Since the file is going to be sent as binary body, it doesn't make
            // any sense to generate `FormData` for it.
            for (let file of files)this.emit("sending", file, xhr);
            if (this.options.uploadMultiple) this.emit("sendingmultiple", files, xhr);
            this.submitRequest(xhr, null, files);
        } else {
            let formData = new FormData();
            // Adding all @options parameters
            if (this.options.params) {
                let additionalParams = this.options.params;
                if (typeof additionalParams === "function") additionalParams = additionalParams.call(this, files, xhr, files[0].upload.chunked ? this._getChunk(files[0], xhr) : null);
                for(let key in additionalParams){
                    let value = additionalParams[key];
                    if (Array.isArray(value)) // The additional parameter contains an array,
                    // so lets iterate over it to attach each value
                    // individually.
                    for(let i = 0; i < value.length; i++)formData.append(key, value[i]);
                    else formData.append(key, value);
                }
            }
            // Let the user add additional data if necessary
            for (let file of files)this.emit("sending", file, xhr, formData);
            if (this.options.uploadMultiple) this.emit("sendingmultiple", files, xhr, formData);
            this._addFormElementData(formData);
            // Finally add the files
            // Has to be last because some servers (eg: S3) expect the file to be the last parameter
            for(let i = 0; i < dataBlocks.length; i++){
                let dataBlock = dataBlocks[i];
                formData.append(dataBlock.name, dataBlock.data, dataBlock.filename);
            }
            this.submitRequest(xhr, formData, files);
        }
    }
    // Transforms all files with this.options.transformFile and invokes done with the transformed files when done.
    _transformFiles(files, done) {
        let transformedFiles = [];
        // Clumsy way of handling asynchronous calls, until I get to add a proper Future library.
        let doneCounter = 0;
        for(let i = 0; i < files.length; i++)this.options.transformFile.call(this, files[i], (transformedFile)=>{
            transformedFiles[i] = transformedFile;
            if (++doneCounter === files.length) done(transformedFiles);
        });
    }
    // Takes care of adding other input elements of the form to the AJAX request
    _addFormElementData(formData) {
        // Take care of other input elements
        if (this.element.tagName === "FORM") for (let input of this.element.querySelectorAll("input, textarea, select, button")){
            let inputName = input.getAttribute("name");
            let inputType = input.getAttribute("type");
            if (inputType) inputType = inputType.toLowerCase();
            // If the input doesn't have a name, we can't use it.
            if (typeof inputName === "undefined" || inputName === null) continue;
            if (input.tagName === "SELECT" && input.hasAttribute("multiple")) {
                // Possibly multiple values
                for (let option of input.options)if (option.selected) formData.append(inputName, option.value);
            } else if (!inputType || inputType !== "checkbox" && inputType !== "radio" || input.checked) formData.append(inputName, input.value);
        }
    }
    // Invoked when there is new progress information about given files.
    // If e is not provided, it is assumed that the upload is finished.
    _updateFilesUploadProgress(files, xhr, e) {
        if (!files[0].upload.chunked) // Handle file uploads without chunking
        for (let file of files){
            if (file.upload.total && file.upload.bytesSent && file.upload.bytesSent == file.upload.total) continue;
            if (e) {
                file.upload.progress = 100 * e.loaded / e.total;
                file.upload.total = e.total;
                file.upload.bytesSent = e.loaded;
            } else {
                // No event, so we're at 100%
                file.upload.progress = 100;
                file.upload.bytesSent = file.upload.total;
            }
            this.emit("uploadprogress", file, file.upload.progress, file.upload.bytesSent);
        }
        else {
            // Handle chunked file uploads
            // Chunked upload is not compatible with uploading multiple files in one
            // request, so we know there's only one file.
            let file = files[0];
            // Since this is a chunked upload, we need to update the appropriate chunk
            // progress.
            let chunk = this._getChunk(file, xhr);
            if (e) {
                chunk.progress = 100 * e.loaded / e.total;
                chunk.total = e.total;
                chunk.bytesSent = e.loaded;
            } else {
                // No event, so we're at 100%
                chunk.progress = 100;
                chunk.bytesSent = chunk.total;
            }
            // Now tally the *file* upload progress from its individual chunks
            file.upload.progress = 0;
            file.upload.total = 0;
            file.upload.bytesSent = 0;
            for(let i = 0; i < file.upload.totalChunkCount; i++)if (file.upload.chunks[i] && typeof file.upload.chunks[i].progress !== "undefined") {
                file.upload.progress += file.upload.chunks[i].progress;
                file.upload.total += file.upload.chunks[i].total;
                file.upload.bytesSent += file.upload.chunks[i].bytesSent;
            }
            // Since the process is a percentage, we need to divide by the amount of
            // chunks we've used.
            file.upload.progress = file.upload.progress / file.upload.totalChunkCount;
            this.emit("uploadprogress", file, file.upload.progress, file.upload.bytesSent);
        }
    }
    _finishedUploading(files, xhr, e) {
        let response;
        if (files[0].status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.CANCELED) return;
        if (xhr.readyState !== 4) return;
        if (xhr.responseType !== "arraybuffer" && xhr.responseType !== "blob") {
            response = xhr.responseText;
            if (xhr.getResponseHeader("content-type") && ~xhr.getResponseHeader("content-type").indexOf("application/json")) try {
                response = JSON.parse(response);
            } catch (error) {
                e = error;
                response = "Invalid JSON response from server.";
            }
        }
        this._updateFilesUploadProgress(files, xhr);
        if (!(200 <= xhr.status && xhr.status < 300)) this._handleUploadError(files, xhr, response);
        else if (files[0].upload.chunked) files[0].upload.finishedChunkUpload(this._getChunk(files[0], xhr), response);
        else this._finished(files, response, e);
    }
    _handleUploadError(files, xhr, response) {
        if (files[0].status === $3ed269f2f0fb224b$export$2e2bcd8739ae039.CANCELED) return;
        if (files[0].upload.chunked && this.options.retryChunks) {
            let chunk = this._getChunk(files[0], xhr);
            if ((chunk.retries++) < this.options.retryChunksLimit) {
                this._uploadData(files, [
                    chunk.dataBlock
                ]);
                return;
            } else console.warn("Retried this chunk too often. Giving up.");
        }
        this._errorProcessing(files, response || this.options.dictResponseError.replace("{{statusCode}}", xhr.status), xhr);
    }
    submitRequest(xhr, formData, files) {
        if (xhr.readyState != 1) {
            console.warn("Cannot send this request because the XMLHttpRequest.readyState is not OPENED.");
            return;
        }
        if (this.options.binaryBody) {
            if (files[0].upload.chunked) {
                const chunk = this._getChunk(files[0], xhr);
                xhr.send(chunk.dataBlock.data);
            } else xhr.send(files[0]);
        } else xhr.send(formData);
    }
    // Called internally when processing is finished.
    // Individual callbacks have to be called in the appropriate sections.
    _finished(files, responseText, e) {
        for (let file of files){
            file.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.SUCCESS;
            this.emit("success", file, responseText, e);
            this.emit("complete", file);
        }
        if (this.options.uploadMultiple) {
            this.emit("successmultiple", files, responseText, e);
            this.emit("completemultiple", files);
        }
        if (this.options.autoProcessQueue) return this.processQueue();
    }
    // Called internally when processing is finished.
    // Individual callbacks have to be called in the appropriate sections.
    _errorProcessing(files, message, xhr) {
        for (let file of files){
            file.status = $3ed269f2f0fb224b$export$2e2bcd8739ae039.ERROR;
            this.emit("error", file, message, xhr);
            this.emit("complete", file);
        }
        if (this.options.uploadMultiple) {
            this.emit("errormultiple", files, message, xhr);
            this.emit("completemultiple", files);
        }
        if (this.options.autoProcessQueue) return this.processQueue();
    }
    static uuidv4() {
        return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function(c) {
            let r = Math.random() * 16 | 0, v = c === "x" ? r : r & 3 | 8;
            return v.toString(16);
        });
    }
    constructor(el, options){
        super();
        let fallback, left;
        this.element = el;
        this.clickableElements = [];
        this.listeners = [];
        this.files = []; // All files
        if (typeof this.element === "string") this.element = document.querySelector(this.element);
        // Not checking if instance of HTMLElement or Element since IE9 is extremely weird.
        if (!this.element || this.element.nodeType == null) throw new Error("Invalid dropzone element.");
        if (this.element.dropzone) throw new Error("Dropzone already attached.");
        // Now add this dropzone to the instances.
        $3ed269f2f0fb224b$export$2e2bcd8739ae039.instances.push(this);
        // Put the dropzone inside the element itself.
        this.element.dropzone = this;
        let elementOptions = (left = $3ed269f2f0fb224b$export$2e2bcd8739ae039.optionsForElement(this.element)) != null ? left : {
        };
        this.options = (0,just_extend__WEBPACK_IMPORTED_MODULE_0__["default"])(true, {
        }, $4ca367182776f80b$export$2e2bcd8739ae039, elementOptions, options != null ? options : {
        });
        this.options.previewTemplate = this.options.previewTemplate.replace(/\n*/g, "");
        // If the browser failed, just call the fallback and leave
        if (this.options.forceFallback || !$3ed269f2f0fb224b$export$2e2bcd8739ae039.isBrowserSupported()) return this.options.fallback.call(this);
        // @options.url = @element.getAttribute "action" unless @options.url?
        if (this.options.url == null) this.options.url = this.element.getAttribute("action");
        if (!this.options.url) throw new Error("No URL provided.");
        if (this.options.acceptedFiles && this.options.acceptedMimeTypes) throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
        if (this.options.uploadMultiple && this.options.chunking) throw new Error("You cannot set both: uploadMultiple and chunking.");
        if (this.options.binaryBody && this.options.uploadMultiple) throw new Error("You cannot set both: binaryBody and uploadMultiple.");
        // Backwards compatibility
        if (this.options.acceptedMimeTypes) {
            this.options.acceptedFiles = this.options.acceptedMimeTypes;
            delete this.options.acceptedMimeTypes;
        }
        // Backwards compatibility
        if (this.options.renameFilename != null) this.options.renameFile = (file)=>this.options.renameFilename.call(this, file.name, file)
        ;
        if (typeof this.options.method === "string") this.options.method = this.options.method.toUpperCase();
        if ((fallback = this.getExistingFallback()) && fallback.parentNode) // Remove the fallback
        fallback.parentNode.removeChild(fallback);
        // Display previews in the previewsContainer element or the Dropzone element unless explicitly set to false
        if (this.options.previewsContainer !== false) {
            if (this.options.previewsContainer) this.previewsContainer = $3ed269f2f0fb224b$export$2e2bcd8739ae039.getElement(this.options.previewsContainer, "previewsContainer");
            else this.previewsContainer = this.element;
        }
        if (this.options.clickable) {
            if (this.options.clickable === true) this.clickableElements = [
                this.element
            ];
            else this.clickableElements = $3ed269f2f0fb224b$export$2e2bcd8739ae039.getElements(this.options.clickable, "clickable");
        }
        this.init();
    }
}
$3ed269f2f0fb224b$export$2e2bcd8739ae039.initClass();
// This is a map of options for your different dropzones. Add configurations
// to this object for your different dropzone elemens.
//
// Example:
//
//     Dropzone.options.myDropzoneElementId = { maxFilesize: 1 };
//
// And in html:
//
//     <form action="/upload" id="my-dropzone-element-id" class="dropzone"></form>
$3ed269f2f0fb224b$export$2e2bcd8739ae039.options = {
};
// Returns the options for an element or undefined if none available.
$3ed269f2f0fb224b$export$2e2bcd8739ae039.optionsForElement = function(element) {
    // Get the `Dropzone.options.elementId` for this element if it exists
    if (element.getAttribute("id")) return $3ed269f2f0fb224b$export$2e2bcd8739ae039.options[$3ed269f2f0fb224b$var$camelize(element.getAttribute("id"))];
    else return undefined;
};
// Holds a list of all dropzone instances
$3ed269f2f0fb224b$export$2e2bcd8739ae039.instances = [];
// Returns the dropzone for given element if any
$3ed269f2f0fb224b$export$2e2bcd8739ae039.forElement = function(element) {
    if (typeof element === "string") element = document.querySelector(element);
    if ((element != null ? element.dropzone : undefined) == null) throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
    return element.dropzone;
};
// Looks for all .dropzone elements and creates a dropzone for them
$3ed269f2f0fb224b$export$2e2bcd8739ae039.discover = function() {
    let dropzones;
    if (document.querySelectorAll) dropzones = document.querySelectorAll(".dropzone");
    else {
        dropzones = [];
        // IE :(
        let checkElements = (elements)=>(()=>{
                let result = [];
                for (let el of elements)if (/(^| )dropzone($| )/.test(el.className)) result.push(dropzones.push(el));
                else result.push(undefined);
                return result;
            })()
        ;
        checkElements(document.getElementsByTagName("div"));
        checkElements(document.getElementsByTagName("form"));
    }
    return (()=>{
        let result = [];
        for (let dropzone of dropzones)// Create a dropzone unless auto discover has been disabled for specific element
        if ($3ed269f2f0fb224b$export$2e2bcd8739ae039.optionsForElement(dropzone) !== false) result.push(new $3ed269f2f0fb224b$export$2e2bcd8739ae039(dropzone));
        else result.push(undefined);
        return result;
    })();
};
// Some browsers support drag and drog functionality, but not correctly.
//
// So I created a blocklist of userAgents. Yes, yes. Browser sniffing, I know.
// But what to do when browsers *theoretically* support an API, but crash
// when using it.
//
// This is a list of regular expressions tested against navigator.userAgent
//
// ** It should only be used on browser that *do* support the API, but
// incorrectly **
$3ed269f2f0fb224b$export$2e2bcd8739ae039.blockedBrowsers = [
    // The mac os and windows phone version of opera 12 seems to have a problem with the File drag'n'drop API.
    /opera.*(Macintosh|Windows Phone).*version\/12/i, 
];
// Checks if the browser is supported
$3ed269f2f0fb224b$export$2e2bcd8739ae039.isBrowserSupported = function() {
    let capableBrowser = true;
    if (window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) {
        if (!("classList" in document.createElement("a"))) capableBrowser = false;
        else {
            if ($3ed269f2f0fb224b$export$2e2bcd8739ae039.blacklistedBrowsers !== undefined) // Since this has been renamed, this makes sure we don't break older
            // configuration.
            $3ed269f2f0fb224b$export$2e2bcd8739ae039.blockedBrowsers = $3ed269f2f0fb224b$export$2e2bcd8739ae039.blacklistedBrowsers;
            // The browser supports the API, but may be blocked.
            for (let regex of $3ed269f2f0fb224b$export$2e2bcd8739ae039.blockedBrowsers)if (regex.test(navigator.userAgent)) {
                capableBrowser = false;
                continue;
            }
        }
    } else capableBrowser = false;
    return capableBrowser;
};
$3ed269f2f0fb224b$export$2e2bcd8739ae039.dataURItoBlob = function(dataURI) {
    // convert base64 to raw binary data held in a string
    // doesn't handle URLEncoded DataURIs - see SO answer #6850276 for code that does this
    let byteString = atob(dataURI.split(",")[1]);
    // separate out the mime component
    let mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];
    // write the bytes of the string to an ArrayBuffer
    let ab = new ArrayBuffer(byteString.length);
    let ia = new Uint8Array(ab);
    for(let i = 0, end = byteString.length, asc = 0 <= end; asc ? i <= end : i >= end; asc ? i++ : i--)ia[i] = byteString.charCodeAt(i);
    // write the ArrayBuffer to a blob
    return new Blob([
        ab
    ], {
        type: mimeString
    });
};
// Returns an array without the rejected item
const $3ed269f2f0fb224b$var$without = (list, rejectedItem)=>list.filter((item)=>item !== rejectedItem
    ).map((item)=>item
    )
;
// abc-def_ghi -> abcDefGhi
const $3ed269f2f0fb224b$var$camelize = (str)=>str.replace(/[\-_](\w)/g, (match)=>match.charAt(1).toUpperCase()
    )
;
// Creates an element from string
$3ed269f2f0fb224b$export$2e2bcd8739ae039.createElement = function(string) {
    let div = document.createElement("div");
    div.innerHTML = string;
    return div.childNodes[0];
};
// Tests if given element is inside (or simply is) the container
$3ed269f2f0fb224b$export$2e2bcd8739ae039.elementInside = function(element, container) {
    if (element === container) return true;
     // Coffeescript doesn't support do/while loops
    while(element = element.parentNode){
        if (element === container) return true;
    }
    return false;
};
$3ed269f2f0fb224b$export$2e2bcd8739ae039.getElement = function(el, name) {
    let element;
    if (typeof el === "string") element = document.querySelector(el);
    else if (el.nodeType != null) element = el;
    if (element == null) throw new Error(`Invalid \`${name}\` option provided. Please provide a CSS selector or a plain HTML element.`);
    return element;
};
$3ed269f2f0fb224b$export$2e2bcd8739ae039.getElements = function(els, name) {
    let el, elements;
    if (els instanceof Array) {
        elements = [];
        try {
            for (el of els)elements.push(this.getElement(el, name));
        } catch (e) {
            elements = null;
        }
    } else if (typeof els === "string") {
        elements = [];
        for (el of document.querySelectorAll(els))elements.push(el);
    } else if (els.nodeType != null) elements = [
        els
    ];
    if (elements == null || !elements.length) throw new Error(`Invalid \`${name}\` option provided. Please provide a CSS selector, a plain HTML element or a list of those.`);
    return elements;
};
// Asks the user the question and calls accepted or rejected accordingly
//
// The default implementation just uses `window.confirm` and then calls the
// appropriate callback.
$3ed269f2f0fb224b$export$2e2bcd8739ae039.confirm = function(question, accepted, rejected) {
    if (window.confirm(question)) return accepted();
    else if (rejected != null) return rejected();
};
// Validates the mime type like this:
//
// https://developer.mozilla.org/en-US/docs/HTML/Element/input#attr-accept
$3ed269f2f0fb224b$export$2e2bcd8739ae039.isValidFile = function(file, acceptedFiles) {
    if (!acceptedFiles) return true;
     // If there are no accepted mime types, it's OK
    acceptedFiles = acceptedFiles.split(",");
    let mimeType = file.type;
    let baseMimeType = mimeType.replace(/\/.*$/, "");
    for (let validType of acceptedFiles){
        validType = validType.trim();
        if (validType.charAt(0) === ".") {
            if (file.name.toLowerCase().indexOf(validType.toLowerCase(), file.name.length - validType.length) !== -1) return true;
        } else if (/\/\*$/.test(validType)) {
            // This is something like a image/* mime type
            if (baseMimeType === validType.replace(/\/.*$/, "")) return true;
        } else {
            if (mimeType === validType) return true;
        }
    }
    return false;
};
// Augment jQuery
if (typeof jQuery !== "undefined" && jQuery !== null) jQuery.fn.dropzone = function(options) {
    return this.each(function() {
        return new $3ed269f2f0fb224b$export$2e2bcd8739ae039(this, options);
    });
};
// Dropzone file status codes
$3ed269f2f0fb224b$export$2e2bcd8739ae039.ADDED = "added";
$3ed269f2f0fb224b$export$2e2bcd8739ae039.QUEUED = "queued";
// For backwards compatibility. Now, if a file is accepted, it's either queued
// or uploading.
$3ed269f2f0fb224b$export$2e2bcd8739ae039.ACCEPTED = $3ed269f2f0fb224b$export$2e2bcd8739ae039.QUEUED;
$3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING = "uploading";
$3ed269f2f0fb224b$export$2e2bcd8739ae039.PROCESSING = $3ed269f2f0fb224b$export$2e2bcd8739ae039.UPLOADING; // alias
$3ed269f2f0fb224b$export$2e2bcd8739ae039.CANCELED = "canceled";
$3ed269f2f0fb224b$export$2e2bcd8739ae039.ERROR = "error";
$3ed269f2f0fb224b$export$2e2bcd8739ae039.SUCCESS = "success";
/*

 Bugfix for iOS 6 and 7
 Source: http://stackoverflow.com/questions/11929099/html5-canvas-drawimage-ratio-bug-ios
 based on the work of https://github.com/stomita/ios-imagefile-megapixel

 */ // Detecting vertical squash in loaded image.
// Fixes a bug which squash image vertically while drawing into canvas for some images.
// This is a bug in iOS6 devices. This function from https://github.com/stomita/ios-imagefile-megapixel
let $3ed269f2f0fb224b$var$detectVerticalSquash = function(img) {
    let iw = img.naturalWidth;
    let ih = img.naturalHeight;
    let canvas = document.createElement("canvas");
    canvas.width = 1;
    canvas.height = ih;
    let ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
    let { data: data  } = ctx.getImageData(1, 0, 1, ih);
    // search image edge pixel position in case it is squashed vertically.
    let sy = 0;
    let ey = ih;
    let py = ih;
    while(py > sy){
        let alpha = data[(py - 1) * 4 + 3];
        if (alpha === 0) ey = py;
        else sy = py;
        py = ey + sy >> 1;
    }
    let ratio = py / ih;
    if (ratio === 0) return 1;
    else return ratio;
};
// A replacement for context.drawImage
// (args are for source and destination).
var $3ed269f2f0fb224b$var$drawImageIOSFix = function(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
    let vertSquashRatio = $3ed269f2f0fb224b$var$detectVerticalSquash(img);
    return ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
};
// Based on MinifyJpeg
// Source: http://www.perry.cz/files/ExifRestorer.js
// http://elicon.blog57.fc2.com/blog-entry-206.html
class $3ed269f2f0fb224b$var$ExifRestore {
    static initClass() {
        this.KEY_STR = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    }
    static encode64(input) {
        let output = "";
        let chr1 = undefined;
        let chr2 = undefined;
        let chr3 = "";
        let enc1 = undefined;
        let enc2 = undefined;
        let enc3 = undefined;
        let enc4 = "";
        let i = 0;
        while(true){
            chr1 = input[i++];
            chr2 = input[i++];
            chr3 = input[i++];
            enc1 = chr1 >> 2;
            enc2 = (chr1 & 3) << 4 | chr2 >> 4;
            enc3 = (chr2 & 15) << 2 | chr3 >> 6;
            enc4 = chr3 & 63;
            if (isNaN(chr2)) enc3 = enc4 = 64;
            else if (isNaN(chr3)) enc4 = 64;
            output = output + this.KEY_STR.charAt(enc1) + this.KEY_STR.charAt(enc2) + this.KEY_STR.charAt(enc3) + this.KEY_STR.charAt(enc4);
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
            if (!(i < input.length)) break;
        }
        return output;
    }
    static restore(origFileBase64, resizedFileBase64) {
        if (!origFileBase64.match("data:image/jpeg;base64,")) return resizedFileBase64;
        let rawImage = this.decode64(origFileBase64.replace("data:image/jpeg;base64,", ""));
        let segments = this.slice2Segments(rawImage);
        let image = this.exifManipulation(resizedFileBase64, segments);
        return `data:image/jpeg;base64,${this.encode64(image)}`;
    }
    static exifManipulation(resizedFileBase64, segments) {
        let exifArray = this.getExifArray(segments);
        let newImageArray = this.insertExif(resizedFileBase64, exifArray);
        let aBuffer = new Uint8Array(newImageArray);
        return aBuffer;
    }
    static getExifArray(segments) {
        let seg = undefined;
        let x = 0;
        while(x < segments.length){
            seg = segments[x];
            if (seg[0] === 255 & seg[1] === 225) return seg;
            x++;
        }
        return [];
    }
    static insertExif(resizedFileBase64, exifArray) {
        let imageData = resizedFileBase64.replace("data:image/jpeg;base64,", "");
        let buf = this.decode64(imageData);
        let separatePoint = buf.indexOf(255, 3);
        let mae = buf.slice(0, separatePoint);
        let ato = buf.slice(separatePoint);
        let array = mae;
        array = array.concat(exifArray);
        array = array.concat(ato);
        return array;
    }
    static slice2Segments(rawImageArray) {
        let head = 0;
        let segments = [];
        while(true){
            var length;
            if (rawImageArray[head] === 255 & rawImageArray[head + 1] === 218) break;
            if (rawImageArray[head] === 255 & rawImageArray[head + 1] === 216) head += 2;
            else {
                length = rawImageArray[head + 2] * 256 + rawImageArray[head + 3];
                let endPoint = head + length + 2;
                let seg = rawImageArray.slice(head, endPoint);
                segments.push(seg);
                head = endPoint;
            }
            if (head > rawImageArray.length) break;
        }
        return segments;
    }
    static decode64(input) {
        let output = "";
        let chr1 = undefined;
        let chr2 = undefined;
        let chr3 = "";
        let enc1 = undefined;
        let enc2 = undefined;
        let enc3 = undefined;
        let enc4 = "";
        let i = 0;
        let buf = [];
        // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
        let base64test = /[^A-Za-z0-9\+\/\=]/g;
        if (base64test.exec(input)) console.warn("There were invalid base64 characters in the input text.\nValid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\nExpect errors in decoding.");
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while(true){
            enc1 = this.KEY_STR.indexOf(input.charAt(i++));
            enc2 = this.KEY_STR.indexOf(input.charAt(i++));
            enc3 = this.KEY_STR.indexOf(input.charAt(i++));
            enc4 = this.KEY_STR.indexOf(input.charAt(i++));
            chr1 = enc1 << 2 | enc2 >> 4;
            chr2 = (enc2 & 15) << 4 | enc3 >> 2;
            chr3 = (enc3 & 3) << 6 | enc4;
            buf.push(chr1);
            if (enc3 !== 64) buf.push(chr2);
            if (enc4 !== 64) buf.push(chr3);
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
            if (!(i < input.length)) break;
        }
        return buf;
    }
}
$3ed269f2f0fb224b$var$ExifRestore.initClass();
/*
 * contentloaded.js
 *
 * Author: Diego Perini (diego.perini at gmail.com)
 * Summary: cross-browser wrapper for DOMContentLoaded
 * Updated: 20101020
 * License: MIT
 * Version: 1.2
 *
 * URL:
 * http://javascript.nwbox.com/ContentLoaded/
 * http://javascript.nwbox.com/ContentLoaded/MIT-LICENSE
 */ // @win window reference
// @fn function reference
let $3ed269f2f0fb224b$var$contentLoaded = function(win, fn) {
    let done = false;
    let top = true;
    let doc = win.document;
    let root = doc.documentElement;
    let add = doc.addEventListener ? "addEventListener" : "attachEvent";
    let rem = doc.addEventListener ? "removeEventListener" : "detachEvent";
    let pre = doc.addEventListener ? "" : "on";
    var init = function(e) {
        if (e.type === "readystatechange" && doc.readyState !== "complete") return;
        (e.type === "load" ? win : doc)[rem](pre + e.type, init, false);
        if (!done && (done = true)) return fn.call(win, e.type || e);
    };
    var poll = function() {
        try {
            root.doScroll("left");
        } catch (e) {
            setTimeout(poll, 50);
            return;
        }
        return init("poll");
    };
    if (doc.readyState !== "complete") {
        if (doc.createEventObject && root.doScroll) {
            try {
                top = !win.frameElement;
            } catch (error) {
            }
            if (top) poll();
        }
        doc[add](pre + "DOMContentLoaded", init, false);
        doc[add](pre + "readystatechange", init, false);
        return win[add](pre + "load", init, false);
    }
};
function $3ed269f2f0fb224b$var$__guard__(value, transform) {
    return typeof value !== "undefined" && value !== null ? transform(value) : undefined;
}
function $3ed269f2f0fb224b$var$__guardMethod__(obj, methodName, transform) {
    if (typeof obj !== "undefined" && obj !== null && typeof obj[methodName] === "function") return transform(obj, methodName);
    else return undefined;
}



//# sourceMappingURL=dropzone.mjs.map


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************************************!*\
  !*** ./resources/assets/admin/js/pages/task.js ***!
  \*************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var _require = __webpack_require__(/*! dropzone */ "./node_modules/dropzone/dist/dropzone.mjs"),
  Dropzone = _require.Dropzone;
var TYPE_CHECKIN = 1;
var TYPE_INSTALL_APP = 2;
var TYPE_VIDEO_WATCH = 3;
var TYPE_SOCIAL = 4;
Dropzone.autoDiscover = false;
__webpack_require__(/*! jquery-repeater-form */ "./node_modules/jquery-repeater-form/jquery.repeater.js");
$(document).ready(function () {
  var taskController = new TaskControls();
});
var TaskControls = /*#__PURE__*/function () {
  function TaskControls() {
    _classCallCheck(this, TaskControls);
    this._initSingleImageUpload();
    this._initGalleries();
    this._initLocation();
    this._changeTaskType();
  }
  /**
   *
   * @private
   */
  _createClass(TaskControls, [{
    key: "_changeTaskType",
    value: function _changeTaskType() {
      var _this = this;
      $('.task-type select').on('change', function (event) {
        var type = $(event.currentTarget).val();
        _this._actionTaskType(type);
      });

      // Get first inital task type
      var typeInit = $('.task-type select option:selected').val();
      if (typeInit) {
        _this._actionTaskType(typeInit);
      }
    }
    /**
     *
     * @private
     */
  }, {
    key: "_actionTaskType",
    value: function _actionTaskType(type) {
      switch (parseInt(type)) {
        case TYPE_CHECKIN:
          $('.wrap-type-checkin').show();
          $('.wrap-type-social').hide();
          break;
        case TYPE_INSTALL_APP:
          break;
        case TYPE_VIDEO_WATCH:
          break;
        case TYPE_SOCIAL:
          $('.wrap-type-social').show();
          $('.wrap-type-checkin').hide();
          break;
        default:
          console.log(type, TYPE_CHECKIN, type == TYPE_CHECKIN);
      }
    }
    /**
     *
     * @private
     */
  }, {
    key: "_initLocation",
    value: function _initLocation() {
      var _this = this;
      $('.js-repeater').repeater({
        isFirstItemUndeletable: true,
        show: function show() {
          var $this = $(this);
          $this.attr('data-repeater-item', '');
          var btnDelete = $this.find('.js-delete');
          if (btnDelete.length) {
            btnDelete.attr('data-repeater-delete', '');
            btnDelete.attr('data-id', '');
            btnDelete.removeClass('js-delete'); //always set bottom of condition
          }

          $this.slideDown();
        }
      });
      $('.js-delete').on('click', function () {
        _this._deleteLocationItem($(this));
      });
    }

    /**
     *
     * @param $this jquery element
     * @private
     */
  }, {
    key: "_deleteLocationItem",
    value: function _deleteLocationItem($this) {
      var locationId = $this.attr('data-id');
      var eFrom = $this.closest('form');
      $this.closest('div[data-repeater-item=' + locationId + ']').remove();
      eFrom.append('<input type="hidden" name="list_delete[]" value="' + locationId + '">');
      return true;
    }

    // Single Image Upload initialization
  }, {
    key: "_initSingleImageUpload",
    value: function _initSingleImageUpload() {
      this._singleImageUploadExample = document.getElementById('taskImgCover');
      var singleImageUpload = new SingleImageUpload(this._singleImageUploadExample);
    }
  }, {
    key: "_initGalleries",
    value: function _initGalleries() {
      new Dropzone('#taskGallery', {
        autoProcessQueue: false,
        maxFilesize: 2,
        dictDefaultMessage: "Drop your files here!",
        url: 'https://httpbin.org/pos',
        // api post file
        removedfile: function removedfile(file) {
          console.log(file.upload.filename);
        },
        init: function init() {
          var myDropzone = this;
          document.querySelector("button[type=submit]").addEventListener("click", function (e) {
            // e.preventDefault();
            // e.stopPropagation();

            // document.querySelector('input[name="gallery"]').files = myDropzone.getQueuedFiles();
            // let pendingFiles = myDropzone.getQueuedFiles();
            // pendingFiles.forEach($file => {
            //   const reader  = new FileReader();
            //   reader.readAsDataURL($('input[name="gallery"]').prop("files"));
            //   console.log($file);
            // });

            //console.log(myDropzone.getQueuedFiles());
            //console.log(myDropzone.getAcceptedFiles());
            //myDropzone.processQueue();
          });
          this.on('success', function (file, responseText) {
            console.log(responseText);
          });
        },
        acceptedFiles: 'image/*',
        thumbnailWidth: 160,
        previewTemplate: DropzoneTemplates.previewTemplate
      });
    }
  }]);
  return TaskControls;
}();
})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiL3N0YXRpYy9qcy9hZG1pbi9wYWdlcy90YXNrLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUNBQXlDLDhCQUE4QjtBQUN2RTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7O0FBRUE7QUFDQTs7QUFFQTtBQUNBLGVBQWU7QUFDZjs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTs7QUFFQTtBQUNBLGVBQWU7QUFDZjs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7O0FBRUE7QUFDQSxlQUFlO0FBQ2Y7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBLGVBQWU7QUFDZjs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsbUJBQW1CLGtDQUFrQztBQUNyRDtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0EsZUFBZTtBQUNmOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EsbUJBQW1CO0FBQ25COztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhOztBQUViO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2I7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUEsbUNBQW1DLFVBQVU7O0FBRTdDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxtQ0FBbUMsVUFBVTtBQUM3QztBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLDJCQUEyQixVQUFVO0FBQ3JDO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLDJCQUEyQixVQUFVO0FBQ3JDO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLDJCQUEyQixVQUFVO0FBQ3JDO0FBQ0E7O0FBRUEsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtB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Q0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsUUFBUSxxQ0FBcUM7QUFDN0M7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxRQUFRLFVBQVUsU0FBUyxhQUFhO0FBQ3hDLDBDQUEwQyxVQUFVLHNCQUFzQixhQUFhO0FBQ3ZGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxRQUFRLFlBQVk7QUFDcEIsa0RBQWtELGFBQWE7QUFDL0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLG1CQUFtQixVQUFVO0FBQzdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLG9DQUFvQyx3QkFBd0I7QUFDNUQ7QUFDQTtBQUNBLDRDQUE0QztBQUM1QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFVBQVU7QUFDVjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCO0FBQ2xCO0FBQ0E7QUFDQTtBQUNBLGNBQWM7QUFDZDtBQUNBO0FBQ0E7QUFDQSxjQUFjLDhDQUE4QyxhQUFhO0FBQ3pFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHdEQUF3RDtBQUN4RDtBQUNBO0FBQ0E7QUFDQTtBQUNBLDJJQUEySSxtQkFBbUIsNEJBQTRCO0FBQzFMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsZ01BQWdNLFNBQVM7QUFDek0sS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLDRDQUE0Qzs7QUFFNUM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscVFBQXFRLGdDQUFnQztBQUNyUztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwwQkFBMEIsZ0JBQWdCO0FBQzFDO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBLDJCQUEyQixpQ0FBaUM7QUFDNUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSwwQkFBMEI7QUFDMUI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw2T0FBNk87QUFDN087QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFVBQVU7QUFDVjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx1QkFBdUIsdUJBQXVCLEVBQUUsa0NBQWtDLEVBQUUsUUFBUTtBQUM1RjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpRUFBaUUsOEJBQThCO0FBQy9GLG9EQUFvRCxzQkFBc0IsSUFBSSxpRUFBaUU7QUFDL0k7QUFDQTtBQUNBLDJGQUEyRixpQkFBaUIsMENBQTBDLG9CQUFvQjtBQUMxSztBQUNBLFVBQVU7QUFDVjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDJCQUEyQixrQkFBa0I7QUFDN0M7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLCtEQUErRDtBQUMvRDtBQUNBLDBCQUEwQixhQUFhLFlBQVksNkNBQTZDO0FBQ2hHO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFVBQVU7QUFDVjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHVCQUF1QixpQ0FBaUM7QUFDeEQ7QUFDQTtBQUNBLGtCQUFrQixnQkFBZ0I7QUFDbEM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxjQUFjLGdCQUFnQjtBQUM5QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCO0FBQ2xCO0FBQ0E7QUFDQSxrQkFBa0I7QUFDbEI7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLCtDQUErQyxLQUFLLEdBQUcsVUFBVTtBQUNqRTtBQUNBLHlCQUF5QjtBQUN6QiwwRkFBMEYsS0FBSyxHQUFHLFdBQVc7QUFDN0c7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxvSUFBb0ksVUFBVSwwREFBMEQsYUFBYTtBQUNyTjtBQUNBO0FBQ0EsOERBQThELFVBQVU7QUFDeEU7QUFDQSxVQUFVO0FBQ1Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDJCQUEyQjtBQUMzQixjQUFjO0FBQ2Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQjtBQUNsQixVQUFVO0FBQ1Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQjtBQUNsQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esc0JBQXNCLGtDQUFrQztBQUN4RDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsVUFBVTtBQUNWO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsY0FBYyxvQ0FBb0M7QUFDbEQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esb0NBQW9DO0FBQ3BDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxVQUFVO0FBQ1Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FB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O1FBQzNDQyxHQUFHLEVBQUUseUJBQXlCO1FBQUU7UUFDaENDLFdBQVcsRUFBRSxxQkFBU0MsSUFBSSxFQUFFO1VBQzFCMUIsT0FBTyxDQUFDQyxHQUFHLENBQUN5QixJQUFJLENBQUNDLE1BQU0sQ0FBQ0MsUUFBUSxDQUFDO1FBQ25DLENBQUM7UUFDREMsSUFBSSxFQUFFLGdCQUFZO1VBQ2hCLElBQUlDLFVBQVUsR0FBRyxJQUFJO1VBQ3JCakQsUUFBUSxDQUFDa0QsYUFBYSxDQUFDLHFCQUFxQixDQUFDLENBQUNDLGdCQUFnQixDQUFDLE9BQU8sRUFBRSxVQUFTQyxDQUFDLEVBQUU7WUFDbEY7WUFDQTs7WUFFQTtZQUNBO1lBQ0E7WUFDQTtZQUNBO1lBQ0E7WUFDQTs7WUFFQTtZQUNBO1lBQ0E7VUFBQSxDQUNELENBQUM7VUFDRixJQUFJLENBQUMzQyxFQUFFLENBQUMsU0FBUyxFQUFFLFVBQVVvQyxJQUFJLEVBQUVRLFlBQVksRUFBRTtZQUMvQ2xDLE9BQU8sQ0FBQ0MsR0FBRyxDQUFDaUMsWUFBWSxDQUFDO1VBQzNCLENBQUMsQ0FBQztRQUNKLENBQUM7UUFDREMsYUFBYSxFQUFFLFNBQVM7UUFDeEJDLGNBQWMsRUFBRSxHQUFHO1FBQ25CQyxlQUFlLEVBQUVDLGlCQUFpQixDQUFDRDtNQUNyQyxDQUFDLENBQUM7SUFDSjtFQUFDO0VBQUE7QUFBQSxJIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2pxdWVyeS1yZXBlYXRlci1mb3JtL2pxdWVyeS5yZXBlYXRlci5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanVzdC1leHRlbmQvaW5kZXguZXNtLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9kcm9wem9uZS9kaXN0L2Ryb3B6b25lLm1qcyIsIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vL3dlYnBhY2svcnVudGltZS9kZWZpbmUgcHJvcGVydHkgZ2V0dGVycyIsIndlYnBhY2s6Ly8vd2VicGFjay9ydW50aW1lL2hhc093blByb3BlcnR5IHNob3J0aGFuZCIsIndlYnBhY2s6Ly8vd2VicGFjay9ydW50aW1lL21ha2UgbmFtZXNwYWNlIG9iamVjdCIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL2FkbWluL2pzL3BhZ2VzL3Rhc2suanMiXSwic291cmNlc0NvbnRlbnQiOlsiLy8ganF1ZXJ5LnJlcGVhdGVyIHZlcnNpb24gMS4yLjFcbi8vIGh0dHBzOi8vZ2l0aHViLmNvbS9EdWJGcmllbmQvanF1ZXJ5LnJlcGVhdGVyXG4vLyAoTUlUKSAwOS0xMC0yMDE2XG4vLyBCcmlhbiBEZXRlcmluZyA8QkRldGVyaW5AZ21haWwuY29tPiAoaHR0cDovL3d3dy5icmlhbmRldGVyaW5nLm5ldC8pXG4oZnVuY3Rpb24gKCQpIHtcbid1c2Ugc3RyaWN0JztcblxudmFyIGlkZW50aXR5ID0gZnVuY3Rpb24gKHgpIHtcbiAgICByZXR1cm4geDtcbn07XG5cbnZhciBpc0FycmF5ID0gZnVuY3Rpb24gKHZhbHVlKSB7XG4gICAgcmV0dXJuICQuaXNBcnJheSh2YWx1ZSk7XG59O1xuXG52YXIgaXNPYmplY3QgPSBmdW5jdGlvbiAodmFsdWUpIHtcbiAgICByZXR1cm4gIWlzQXJyYXkodmFsdWUpICYmICh2YWx1ZSBpbnN0YW5jZW9mIE9iamVjdCk7XG59O1xuXG52YXIgaXNOdW1iZXIgPSBmdW5jdGlvbiAodmFsdWUpIHtcbiAgICByZXR1cm4gdmFsdWUgaW5zdGFuY2VvZiBOdW1iZXI7XG59O1xuXG52YXIgaXNGdW5jdGlvbiA9IGZ1bmN0aW9uICh2YWx1ZSkge1xuICAgIHJldHVybiB2YWx1ZSBpbnN0YW5jZW9mIEZ1bmN0aW9uO1xufTtcblxudmFyIGluZGV4T2YgPSBmdW5jdGlvbiAob2JqZWN0LCB2YWx1ZSkge1xuICAgIHJldHVybiAkLmluQXJyYXkodmFsdWUsIG9iamVjdCk7XG59O1xuXG52YXIgaW5BcnJheSA9IGZ1bmN0aW9uIChhcnJheSwgdmFsdWUpIHtcbiAgICByZXR1cm4gaW5kZXhPZihhcnJheSwgdmFsdWUpICE9PSAtMTtcbn07XG5cbnZhciBmb3JlYWNoID0gZnVuY3Rpb24gKGNvbGxlY3Rpb24sIGNhbGxiYWNrKSB7XG4gICAgZm9yKHZhciBpIGluIGNvbGxlY3Rpb24pIHtcbiAgICAgICAgaWYoY29sbGVjdGlvbi5oYXNPd25Qcm9wZXJ0eShpKSkge1xuICAgICAgICAgICAgY2FsbGJhY2soY29sbGVjdGlvbltpXSwgaSwgY29sbGVjdGlvbik7XG4gICAgICAgIH1cbiAgICB9XG59O1xuXG5cbnZhciBsYXN0ID0gZnVuY3Rpb24gKGFycmF5KSB7XG4gICAgcmV0dXJuIGFycmF5W2FycmF5Lmxlbmd0aCAtIDFdO1xufTtcblxudmFyIGFyZ3VtZW50c1RvQXJyYXkgPSBmdW5jdGlvbiAoYXJncykge1xuICAgIHJldHVybiBBcnJheS5wcm90b3R5cGUuc2xpY2UuY2FsbChhcmdzKTtcbn07XG5cbnZhciBleHRlbmQgPSBmdW5jdGlvbiAoKSB7XG4gICAgdmFyIGV4dGVuZGVkID0ge307XG4gICAgZm9yZWFjaChhcmd1bWVudHNUb0FycmF5KGFyZ3VtZW50cyksIGZ1bmN0aW9uIChvKSB7XG4gICAgICAgIGZvcmVhY2gobywgZnVuY3Rpb24gKHZhbCwga2V5KSB7XG4gICAgICAgICAgICBleHRlbmRlZFtrZXldID0gdmFsO1xuICAgICAgICB9KTtcbiAgICB9KTtcbiAgICByZXR1cm4gZXh0ZW5kZWQ7XG59O1xuXG52YXIgbWFwVG9BcnJheSA9IGZ1bmN0aW9uIChjb2xsZWN0aW9uLCBjYWxsYmFjaykge1xuICAgIHZhciBtYXBwZWQgPSBbXTtcbiAgICBmb3JlYWNoKGNvbGxlY3Rpb24sIGZ1bmN0aW9uICh2YWx1ZSwga2V5LCBjb2xsKSB7XG4gICAgICAgIG1hcHBlZC5wdXNoKGNhbGxiYWNrKHZhbHVlLCBrZXksIGNvbGwpKTtcbiAgICB9KTtcbiAgICByZXR1cm4gbWFwcGVkO1xufTtcblxudmFyIG1hcFRvT2JqZWN0ID0gZnVuY3Rpb24gKGNvbGxlY3Rpb24sIGNhbGxiYWNrLCBrZXlDYWxsYmFjaykge1xuICAgIHZhciBtYXBwZWQgPSB7fTtcbiAgICBmb3JlYWNoKGNvbGxlY3Rpb24sIGZ1bmN0aW9uICh2YWx1ZSwga2V5LCBjb2xsKSB7XG4gICAgICAgIGtleSA9IGtleUNhbGxiYWNrID8ga2V5Q2FsbGJhY2soa2V5LCB2YWx1ZSkgOiBrZXk7XG4gICAgICAgIG1hcHBlZFtrZXldID0gY2FsbGJhY2sodmFsdWUsIGtleSwgY29sbCk7XG4gICAgfSk7XG4gICAgcmV0dXJuIG1hcHBlZDtcbn07XG5cbnZhciBtYXAgPSBmdW5jdGlvbiAoY29sbGVjdGlvbiwgY2FsbGJhY2ssIGtleUNhbGxiYWNrKSB7XG4gICAgcmV0dXJuIGlzQXJyYXkoY29sbGVjdGlvbikgP1xuICAgICAgICBtYXBUb0FycmF5KGNvbGxlY3Rpb24sIGNhbGxiYWNrKSA6XG4gICAgICAgIG1hcFRvT2JqZWN0KGNvbGxlY3Rpb24sIGNhbGxiYWNrLCBrZXlDYWxsYmFjayk7XG59O1xuXG52YXIgcGx1Y2sgPSBmdW5jdGlvbiAoYXJyYXlPZk9iamVjdHMsIGtleSkge1xuICAgIHJldHVybiBtYXAoYXJyYXlPZk9iamVjdHMsIGZ1bmN0aW9uICh2YWwpIHtcbiAgICAgICAgcmV0dXJuIHZhbFtrZXldO1xuICAgIH0pO1xufTtcblxudmFyIGZpbHRlciA9IGZ1bmN0aW9uIChjb2xsZWN0aW9uLCBjYWxsYmFjaykge1xuICAgIHZhciBmaWx0ZXJlZDtcblxuICAgIGlmKGlzQXJyYXkoY29sbGVjdGlvbikpIHtcbiAgICAgICAgZmlsdGVyZWQgPSBbXTtcbiAgICAgICAgZm9yZWFjaChjb2xsZWN0aW9uLCBmdW5jdGlvbiAodmFsLCBrZXksIGNvbGwpIHtcbiAgICAgICAgICAgIGlmKGNhbGxiYWNrKHZhbCwga2V5LCBjb2xsKSkge1xuICAgICAgICAgICAgICAgIGZpbHRlcmVkLnB1c2godmFsKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgICBmaWx0ZXJlZCA9IHt9O1xuICAgICAgICBmb3JlYWNoKGNvbGxlY3Rpb24sIGZ1bmN0aW9uICh2YWwsIGtleSwgY29sbCkge1xuICAgICAgICAgICAgaWYoY2FsbGJhY2sodmFsLCBrZXksIGNvbGwpKSB7XG4gICAgICAgICAgICAgICAgZmlsdGVyZWRba2V5XSA9IHZhbDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgcmV0dXJuIGZpbHRlcmVkO1xufTtcblxudmFyIGNhbGwgPSBmdW5jdGlvbiAoY29sbGVjdGlvbiwgZnVuY3Rpb25OYW1lLCBhcmdzKSB7XG4gICAgcmV0dXJuIG1hcChjb2xsZWN0aW9uLCBmdW5jdGlvbiAob2JqZWN0LCBuYW1lKSB7XG4gICAgICAgIHJldHVybiBvYmplY3RbZnVuY3Rpb25OYW1lXS5hcHBseShvYmplY3QsIGFyZ3MgfHwgW10pO1xuICAgIH0pO1xufTtcblxuLy9leGVjdXRlIGNhbGxiYWNrIGltbWVkaWF0ZWx5IGFuZCBhdCBtb3N0IG9uZSB0aW1lIG9uIHRoZSBtaW5pbXVtSW50ZXJ2YWwsXG4vL2lnbm9yZSBibG9jayBhdHRlbXB0c1xudmFyIHRocm90dGxlID0gZnVuY3Rpb24gKG1pbmltdW1JbnRlcnZhbCwgY2FsbGJhY2spIHtcbiAgICB2YXIgdGltZW91dCA9IG51bGw7XG4gICAgcmV0dXJuIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHRoYXQgPSB0aGlzLCBhcmdzID0gYXJndW1lbnRzO1xuICAgICAgICBpZih0aW1lb3V0ID09PSBudWxsKSB7XG4gICAgICAgICAgICB0aW1lb3V0ID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdGltZW91dCA9IG51bGw7XG4gICAgICAgICAgICB9LCBtaW5pbXVtSW50ZXJ2YWwpO1xuICAgICAgICAgICAgY2FsbGJhY2suYXBwbHkodGhhdCwgYXJncyk7XG4gICAgICAgIH1cbiAgICB9O1xufTtcblxuXG52YXIgbWl4aW5QdWJTdWIgPSBmdW5jdGlvbiAob2JqZWN0KSB7XG4gICAgb2JqZWN0ID0gb2JqZWN0IHx8IHt9O1xuICAgIHZhciB0b3BpY3MgPSB7fTtcblxuICAgIG9iamVjdC5wdWJsaXNoID0gZnVuY3Rpb24gKHRvcGljLCBkYXRhKSB7XG4gICAgICAgIGZvcmVhY2godG9waWNzW3RvcGljXSwgZnVuY3Rpb24gKGNhbGxiYWNrKSB7XG4gICAgICAgICAgICBjYWxsYmFjayhkYXRhKTtcbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIG9iamVjdC5zdWJzY3JpYmUgPSBmdW5jdGlvbiAodG9waWMsIGNhbGxiYWNrKSB7XG4gICAgICAgIHRvcGljc1t0b3BpY10gPSB0b3BpY3NbdG9waWNdIHx8IFtdO1xuICAgICAgICB0b3BpY3NbdG9waWNdLnB1c2goY2FsbGJhY2spO1xuICAgIH07XG5cbiAgICBvYmplY3QudW5zdWJzY3JpYmUgPSBmdW5jdGlvbiAoY2FsbGJhY2spIHtcbiAgICAgICAgZm9yZWFjaCh0b3BpY3MsIGZ1bmN0aW9uIChzdWJzY3JpYmVycykge1xuICAgICAgICAgICAgdmFyIGluZGV4ID0gaW5kZXhPZihzdWJzY3JpYmVycywgY2FsbGJhY2spO1xuICAgICAgICAgICAgaWYoaW5kZXggIT09IC0xKSB7XG4gICAgICAgICAgICAgICAgc3Vic2NyaWJlcnMuc3BsaWNlKGluZGV4LCAxKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIHJldHVybiBvYmplY3Q7XG59O1xuXG4vLyBqcXVlcnkuaW5wdXQgdmVyc2lvbiAwLjAuMFxuLy8gaHR0cHM6Ly9naXRodWIuY29tL0R1YkZyaWVuZC9qcXVlcnkuaW5wdXRcbi8vIChNSVQpIDA5LTA0LTIwMTRcbi8vIEJyaWFuIERldGVyaW5nIDxCRGV0ZXJpbkBnbWFpbC5jb20+IChodHRwOi8vd3d3LmJyaWFuZGV0ZXJpbmcubmV0LylcbihmdW5jdGlvbiAoJCkge1xuJ3VzZSBzdHJpY3QnO1xuXG52YXIgY3JlYXRlQmFzZUlucHV0ID0gZnVuY3Rpb24gKGZpZywgbXkpIHtcbiAgICB2YXIgc2VsZiA9IG1peGluUHViU3ViKCksXG4gICAgICAgICRzZWxmID0gZmlnLiQ7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRocm93ICdpbXBsZW1lbnQgbWUgKHJldHVybiB0eXBlLiBcInRleHRcIiwgXCJyYWRpb1wiLCBldGMuKSc7XG4gICAgfTtcblxuICAgIHNlbGYuJCA9IGZ1bmN0aW9uIChzZWxlY3Rvcikge1xuICAgICAgICByZXR1cm4gc2VsZWN0b3IgPyAkc2VsZi5maW5kKHNlbGVjdG9yKSA6ICRzZWxmO1xuICAgIH07XG5cbiAgICBzZWxmLmRpc2FibGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHNlbGYuJCgpLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gICAgICAgIHNlbGYucHVibGlzaCgnaXNFbmFibGVkJywgZmFsc2UpO1xuICAgIH07XG5cbiAgICBzZWxmLmVuYWJsZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgc2VsZi4kKCkucHJvcCgnZGlzYWJsZWQnLCBmYWxzZSk7XG4gICAgICAgIHNlbGYucHVibGlzaCgnaXNFbmFibGVkJywgdHJ1ZSk7XG4gICAgfTtcblxuICAgIG15LmVxdWFsVG8gPSBmdW5jdGlvbiAoYSwgYikge1xuICAgICAgICByZXR1cm4gYSA9PT0gYjtcbiAgICB9O1xuXG4gICAgbXkucHVibGlzaENoYW5nZSA9IChmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBvbGRWYWx1ZTtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uIChlLCBkb21FbGVtZW50KSB7XG4gICAgICAgICAgICB2YXIgbmV3VmFsdWUgPSBzZWxmLmdldCgpO1xuICAgICAgICAgICAgaWYoIW15LmVxdWFsVG8obmV3VmFsdWUsIG9sZFZhbHVlKSkge1xuICAgICAgICAgICAgICAgIHNlbGYucHVibGlzaCgnY2hhbmdlJywgeyBlOiBlLCBkb21FbGVtZW50OiBkb21FbGVtZW50IH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgb2xkVmFsdWUgPSBuZXdWYWx1ZTtcbiAgICAgICAgfTtcbiAgICB9KCkpO1xuXG4gICAgcmV0dXJuIHNlbGY7XG59O1xuXG5cbnZhciBjcmVhdGVJbnB1dCA9IGZ1bmN0aW9uIChmaWcsIG15KSB7XG4gICAgdmFyIHNlbGYgPSBjcmVhdGVCYXNlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIHNlbGYuJCgpLnZhbCgpO1xuICAgIH07XG5cbiAgICBzZWxmLnNldCA9IGZ1bmN0aW9uIChuZXdWYWx1ZSkge1xuICAgICAgICBzZWxmLiQoKS52YWwobmV3VmFsdWUpO1xuICAgIH07XG5cbiAgICBzZWxmLmNsZWFyID0gZnVuY3Rpb24gKCkge1xuICAgICAgICBzZWxmLnNldCgnJyk7XG4gICAgfTtcblxuICAgIG15LmJ1aWxkU2V0dGVyID0gZnVuY3Rpb24gKGNhbGxiYWNrKSB7XG4gICAgICAgIHJldHVybiBmdW5jdGlvbiAobmV3VmFsdWUpIHtcbiAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoc2VsZiwgbmV3VmFsdWUpO1xuICAgICAgICB9O1xuICAgIH07XG5cbiAgICByZXR1cm4gc2VsZjtcbn07XG5cbnZhciBpbnB1dEVxdWFsVG9BcnJheSA9IGZ1bmN0aW9uIChhLCBiKSB7XG4gICAgYSA9IGlzQXJyYXkoYSkgPyBhIDogW2FdO1xuICAgIGIgPSBpc0FycmF5KGIpID8gYiA6IFtiXTtcblxuICAgIHZhciBpc0VxdWFsID0gdHJ1ZTtcbiAgICBpZihhLmxlbmd0aCAhPT0gYi5sZW5ndGgpIHtcbiAgICAgICAgaXNFcXVhbCA9IGZhbHNlO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgICAgZm9yZWFjaChhLCBmdW5jdGlvbiAodmFsdWUpIHtcbiAgICAgICAgICAgIGlmKCFpbkFycmF5KGIsIHZhbHVlKSkge1xuICAgICAgICAgICAgICAgIGlzRXF1YWwgPSBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgcmV0dXJuIGlzRXF1YWw7XG59O1xuXG52YXIgY3JlYXRlSW5wdXRCdXR0b24gPSBmdW5jdGlvbiAoZmlnKSB7XG4gICAgdmFyIG15ID0ge30sXG4gICAgICAgIHNlbGYgPSBjcmVhdGVJbnB1dChmaWcsIG15KTtcblxuICAgIHNlbGYuZ2V0VHlwZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuICdidXR0b24nO1xuICAgIH07XG5cbiAgICBzZWxmLiQoKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgbXkucHVibGlzaENoYW5nZShlLCB0aGlzKTtcbiAgICB9KTtcblxuICAgIHJldHVybiBzZWxmO1xufTtcblxudmFyIGNyZWF0ZUlucHV0Q2hlY2tib3ggPSBmdW5jdGlvbiAoZmlnKSB7XG4gICAgdmFyIG15ID0ge30sXG4gICAgICAgIHNlbGYgPSBjcmVhdGVJbnB1dChmaWcsIG15KTtcblxuICAgIHNlbGYuZ2V0VHlwZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuICdjaGVja2JveCc7XG4gICAgfTtcblxuICAgIHNlbGYuZ2V0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgdmFsdWVzID0gW107XG4gICAgICAgIHNlbGYuJCgpLmZpbHRlcignOmNoZWNrZWQnKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhbHVlcy5wdXNoKCQodGhpcykudmFsKCkpO1xuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIHZhbHVlcztcbiAgICB9O1xuXG4gICAgc2VsZi5zZXQgPSBmdW5jdGlvbiAobmV3VmFsdWVzKSB7XG4gICAgICAgIG5ld1ZhbHVlcyA9IGlzQXJyYXkobmV3VmFsdWVzKSA/IG5ld1ZhbHVlcyA6IFtuZXdWYWx1ZXNdO1xuXG4gICAgICAgIHNlbGYuJCgpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCh0aGlzKS5wcm9wKCdjaGVja2VkJywgZmFsc2UpO1xuICAgICAgICB9KTtcblxuICAgICAgICBmb3JlYWNoKG5ld1ZhbHVlcywgZnVuY3Rpb24gKHZhbHVlKSB7XG4gICAgICAgICAgICBzZWxmLiQoKS5maWx0ZXIoJ1t2YWx1ZT1cIicgKyB2YWx1ZSArICdcIl0nKVxuICAgICAgICAgICAgICAgIC5wcm9wKCdjaGVja2VkJywgdHJ1ZSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBteS5lcXVhbFRvID0gaW5wdXRFcXVhbFRvQXJyYXk7XG5cbiAgICBzZWxmLiQoKS5jaGFuZ2UoZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgbXkucHVibGlzaENoYW5nZShlLCB0aGlzKTtcbiAgICB9KTtcblxuICAgIHJldHVybiBzZWxmO1xufTtcblxudmFyIGNyZWF0ZUlucHV0RW1haWwgPSBmdW5jdGlvbiAoZmlnKSB7XG4gICAgdmFyIG15ID0ge30sXG4gICAgICAgIHNlbGYgPSBjcmVhdGVJbnB1dFRleHQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAnZW1haWwnO1xuICAgIH07XG5cbiAgICByZXR1cm4gc2VsZjtcbn07XG5cbnZhciBjcmVhdGVJbnB1dEZpbGUgPSBmdW5jdGlvbiAoZmlnKSB7XG4gICAgdmFyIG15ID0ge30sXG4gICAgICAgIHNlbGYgPSBjcmVhdGVCYXNlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAnZmlsZSc7XG4gICAgfTtcblxuICAgIHNlbGYuZ2V0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gbGFzdChzZWxmLiQoKS52YWwoKS5zcGxpdCgnXFxcXCcpKTtcbiAgICB9O1xuXG4gICAgc2VsZi5jbGVhciA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLy8gaHR0cDovL3N0YWNrb3ZlcmZsb3cuY29tL3F1ZXN0aW9ucy8xMDQzOTU3L2NsZWFyaW5nLWlucHV0LXR5cGUtZmlsZS11c2luZy1qcXVlcnlcbiAgICAgICAgdGhpcy4kKCkuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAkKHRoaXMpLndyYXAoJzxmb3JtPicpLmNsb3Nlc3QoJ2Zvcm0nKS5nZXQoMCkucmVzZXQoKTtcbiAgICAgICAgICAgICQodGhpcykudW53cmFwKCk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICBzZWxmLiQoKS5jaGFuZ2UoZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgbXkucHVibGlzaENoYW5nZShlLCB0aGlzKTtcbiAgICAgICAgLy8gc2VsZi5wdWJsaXNoKCdjaGFuZ2UnLCBzZWxmKTtcbiAgICB9KTtcblxuICAgIHJldHVybiBzZWxmO1xufTtcblxudmFyIGNyZWF0ZUlucHV0SGlkZGVuID0gZnVuY3Rpb24gKGZpZykge1xuICAgIHZhciBteSA9IHt9LFxuICAgICAgICBzZWxmID0gY3JlYXRlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAnaGlkZGVuJztcbiAgICB9O1xuXG4gICAgc2VsZi4kKCkuY2hhbmdlKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIG15LnB1Ymxpc2hDaGFuZ2UoZSwgdGhpcyk7XG4gICAgfSk7XG5cbiAgICByZXR1cm4gc2VsZjtcbn07XG52YXIgY3JlYXRlSW5wdXRNdWx0aXBsZUZpbGUgPSBmdW5jdGlvbiAoZmlnKSB7XG4gICAgdmFyIG15ID0ge30sXG4gICAgICAgIHNlbGYgPSBjcmVhdGVCYXNlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAnZmlsZVttdWx0aXBsZV0nO1xuICAgIH07XG5cbiAgICBzZWxmLmdldCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLy8gaHR0cDovL3N0YWNrb3ZlcmZsb3cuY29tL3F1ZXN0aW9ucy8xNDAzNTUzMC9ob3ctdG8tZ2V0LXZhbHVlLW9mLWh0bWwtNS1tdWx0aXBsZS1maWxlLXVwbG9hZC12YXJpYWJsZS11c2luZy1qcXVlcnlcbiAgICAgICAgdmFyIGZpbGVMaXN0T2JqZWN0ID0gc2VsZi4kKCkuZ2V0KDApLmZpbGVzIHx8IFtdLFxuICAgICAgICAgICAgbmFtZXMgPSBbXSwgaTtcblxuICAgICAgICBmb3IoaSA9IDA7IGkgPCAoZmlsZUxpc3RPYmplY3QubGVuZ3RoIHx8IDApOyBpICs9IDEpIHtcbiAgICAgICAgICAgIG5hbWVzLnB1c2goZmlsZUxpc3RPYmplY3RbaV0ubmFtZSk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gbmFtZXM7XG4gICAgfTtcblxuICAgIHNlbGYuY2xlYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIC8vIGh0dHA6Ly9zdGFja292ZXJmbG93LmNvbS9xdWVzdGlvbnMvMTA0Mzk1Ny9jbGVhcmluZy1pbnB1dC10eXBlLWZpbGUtdXNpbmctanF1ZXJ5XG4gICAgICAgIHRoaXMuJCgpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCh0aGlzKS53cmFwKCc8Zm9ybT4nKS5jbG9zZXN0KCdmb3JtJykuZ2V0KDApLnJlc2V0KCk7XG4gICAgICAgICAgICAkKHRoaXMpLnVud3JhcCgpO1xuICAgICAgICB9KTtcbiAgICB9O1xuXG4gICAgc2VsZi4kKCkuY2hhbmdlKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIG15LnB1Ymxpc2hDaGFuZ2UoZSwgdGhpcyk7XG4gICAgfSk7XG5cbiAgICByZXR1cm4gc2VsZjtcbn07XG5cbnZhciBjcmVhdGVJbnB1dE11bHRpcGxlU2VsZWN0ID0gZnVuY3Rpb24gKGZpZykge1xuICAgIHZhciBteSA9IHt9LFxuICAgICAgICBzZWxmID0gY3JlYXRlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAnc2VsZWN0W211bHRpcGxlXSc7XG4gICAgfTtcblxuICAgIHNlbGYuZ2V0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gc2VsZi4kKCkudmFsKCkgfHwgW107XG4gICAgfTtcblxuICAgIHNlbGYuc2V0ID0gZnVuY3Rpb24gKG5ld1ZhbHVlcykge1xuICAgICAgICBzZWxmLiQoKS52YWwoXG4gICAgICAgICAgICBuZXdWYWx1ZXMgPT09ICcnID8gW10gOiBpc0FycmF5KG5ld1ZhbHVlcykgPyBuZXdWYWx1ZXMgOiBbbmV3VmFsdWVzXVxuICAgICAgICApO1xuICAgIH07XG5cbiAgICBteS5lcXVhbFRvID0gaW5wdXRFcXVhbFRvQXJyYXk7XG5cbiAgICBzZWxmLiQoKS5jaGFuZ2UoZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgbXkucHVibGlzaENoYW5nZShlLCB0aGlzKTtcbiAgICB9KTtcblxuICAgIHJldHVybiBzZWxmO1xufTtcblxudmFyIGNyZWF0ZUlucHV0UGFzc3dvcmQgPSBmdW5jdGlvbiAoZmlnKSB7XG4gICAgdmFyIG15ID0ge30sXG4gICAgICAgIHNlbGYgPSBjcmVhdGVJbnB1dFRleHQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAncGFzc3dvcmQnO1xuICAgIH07XG5cbiAgICByZXR1cm4gc2VsZjtcbn07XG5cbnZhciBjcmVhdGVJbnB1dFJhZGlvID0gZnVuY3Rpb24gKGZpZykge1xuICAgIHZhciBteSA9IHt9LFxuICAgICAgICBzZWxmID0gY3JlYXRlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAncmFkaW8nO1xuICAgIH07XG5cbiAgICBzZWxmLmdldCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmV0dXJuIHNlbGYuJCgpLmZpbHRlcignOmNoZWNrZWQnKS52YWwoKSB8fCBudWxsO1xuICAgIH07XG5cbiAgICBzZWxmLnNldCA9IGZ1bmN0aW9uIChuZXdWYWx1ZSkge1xuICAgICAgICBpZighbmV3VmFsdWUpIHtcbiAgICAgICAgICAgIHNlbGYuJCgpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICQodGhpcykucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgc2VsZi4kKCkuZmlsdGVyKCdbdmFsdWU9XCInICsgbmV3VmFsdWUgKyAnXCJdJykucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIHNlbGYuJCgpLmNoYW5nZShmdW5jdGlvbiAoZSkge1xuICAgICAgICBteS5wdWJsaXNoQ2hhbmdlKGUsIHRoaXMpO1xuICAgIH0pO1xuXG4gICAgcmV0dXJuIHNlbGY7XG59O1xuXG52YXIgY3JlYXRlSW5wdXRSYW5nZSA9IGZ1bmN0aW9uIChmaWcpIHtcbiAgICB2YXIgbXkgPSB7fSxcbiAgICAgICAgc2VsZiA9IGNyZWF0ZUlucHV0KGZpZywgbXkpO1xuXG4gICAgc2VsZi5nZXRUeXBlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gJ3JhbmdlJztcbiAgICB9O1xuXG4gICAgc2VsZi4kKCkuY2hhbmdlKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIG15LnB1Ymxpc2hDaGFuZ2UoZSwgdGhpcyk7XG4gICAgfSk7XG5cbiAgICByZXR1cm4gc2VsZjtcbn07XG5cbnZhciBjcmVhdGVJbnB1dFNlbGVjdCA9IGZ1bmN0aW9uIChmaWcpIHtcbiAgICB2YXIgbXkgPSB7fSxcbiAgICAgICAgc2VsZiA9IGNyZWF0ZUlucHV0KGZpZywgbXkpO1xuXG4gICAgc2VsZi5nZXRUeXBlID0gZnVuY3Rpb24gKCkge1xuICAgICAgICByZXR1cm4gJ3NlbGVjdCc7XG4gICAgfTtcblxuICAgIHNlbGYuJCgpLmNoYW5nZShmdW5jdGlvbiAoZSkge1xuICAgICAgICBteS5wdWJsaXNoQ2hhbmdlKGUsIHRoaXMpO1xuICAgIH0pO1xuXG4gICAgcmV0dXJuIHNlbGY7XG59O1xuXG52YXIgY3JlYXRlSW5wdXRUZXh0ID0gZnVuY3Rpb24gKGZpZykge1xuICAgIHZhciBteSA9IHt9LFxuICAgICAgICBzZWxmID0gY3JlYXRlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAndGV4dCc7XG4gICAgfTtcblxuICAgIHNlbGYuJCgpLm9uKCdjaGFuZ2Uga2V5dXAga2V5ZG93bicsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIG15LnB1Ymxpc2hDaGFuZ2UoZSwgdGhpcyk7XG4gICAgfSk7XG5cbiAgICByZXR1cm4gc2VsZjtcbn07XG5cbnZhciBjcmVhdGVJbnB1dFRleHRhcmVhID0gZnVuY3Rpb24gKGZpZykge1xuICAgIHZhciBteSA9IHt9LFxuICAgICAgICBzZWxmID0gY3JlYXRlSW5wdXQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAndGV4dGFyZWEnO1xuICAgIH07XG5cbiAgICBzZWxmLiQoKS5vbignY2hhbmdlIGtleXVwIGtleWRvd24nLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICBteS5wdWJsaXNoQ2hhbmdlKGUsIHRoaXMpO1xuICAgIH0pO1xuXG4gICAgcmV0dXJuIHNlbGY7XG59O1xuXG52YXIgY3JlYXRlSW5wdXRVUkwgPSBmdW5jdGlvbiAoZmlnKSB7XG4gICAgdmFyIG15ID0ge30sXG4gICAgICAgIHNlbGYgPSBjcmVhdGVJbnB1dFRleHQoZmlnLCBteSk7XG5cbiAgICBzZWxmLmdldFR5cGUgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHJldHVybiAndXJsJztcbiAgICB9O1xuXG4gICAgcmV0dXJuIHNlbGY7XG59O1xuXG52YXIgYnVpbGRGb3JtSW5wdXRzID0gZnVuY3Rpb24gKGZpZykge1xuICAgIHZhciBpbnB1dHMgPSB7fSxcbiAgICAgICAgJHNlbGYgPSBmaWcuJDtcblxuICAgIHZhciBjb25zdHJ1Y3RvciA9IGZpZy5jb25zdHJ1Y3Rvck92ZXJyaWRlIHx8IHtcbiAgICAgICAgYnV0dG9uOiBjcmVhdGVJbnB1dEJ1dHRvbixcbiAgICAgICAgdGV4dDogY3JlYXRlSW5wdXRUZXh0LFxuICAgICAgICB1cmw6IGNyZWF0ZUlucHV0VVJMLFxuICAgICAgICBlbWFpbDogY3JlYXRlSW5wdXRFbWFpbCxcbiAgICAgICAgcGFzc3dvcmQ6IGNyZWF0ZUlucHV0UGFzc3dvcmQsXG4gICAgICAgIHJhbmdlOiBjcmVhdGVJbnB1dFJhbmdlLFxuICAgICAgICB0ZXh0YXJlYTogY3JlYXRlSW5wdXRUZXh0YXJlYSxcbiAgICAgICAgc2VsZWN0OiBjcmVhdGVJbnB1dFNlbGVjdCxcbiAgICAgICAgJ3NlbGVjdFttdWx0aXBsZV0nOiBjcmVhdGVJbnB1dE11bHRpcGxlU2VsZWN0LFxuICAgICAgICByYWRpbzogY3JlYXRlSW5wdXRSYWRpbyxcbiAgICAgICAgY2hlY2tib3g6IGNyZWF0ZUlucHV0Q2hlY2tib3gsXG4gICAgICAgIGZpbGU6IGNyZWF0ZUlucHV0RmlsZSxcbiAgICAgICAgJ2ZpbGVbbXVsdGlwbGVdJzogY3JlYXRlSW5wdXRNdWx0aXBsZUZpbGUsXG4gICAgICAgIGhpZGRlbjogY3JlYXRlSW5wdXRIaWRkZW5cbiAgICB9O1xuXG4gICAgdmFyIGFkZElucHV0c0Jhc2ljID0gZnVuY3Rpb24gKHR5cGUsIHNlbGVjdG9yKSB7XG4gICAgICAgIHZhciAkaW5wdXQgPSBpc09iamVjdChzZWxlY3RvcikgPyBzZWxlY3RvciA6ICRzZWxmLmZpbmQoc2VsZWN0b3IpO1xuXG4gICAgICAgICRpbnB1dC5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBuYW1lID0gJCh0aGlzKS5hdHRyKCduYW1lJyk7XG4gICAgICAgICAgICBpbnB1dHNbbmFtZV0gPSBjb25zdHJ1Y3Rvclt0eXBlXSh7XG4gICAgICAgICAgICAgICAgJDogJCh0aGlzKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH07XG5cbiAgICB2YXIgYWRkSW5wdXRzR3JvdXAgPSBmdW5jdGlvbiAodHlwZSwgc2VsZWN0b3IpIHtcbiAgICAgICAgdmFyIG5hbWVzID0gW10sXG4gICAgICAgICAgICAkaW5wdXQgPSBpc09iamVjdChzZWxlY3RvcikgPyBzZWxlY3RvciA6ICRzZWxmLmZpbmQoc2VsZWN0b3IpO1xuXG4gICAgICAgIGlmKGlzT2JqZWN0KHNlbGVjdG9yKSkge1xuICAgICAgICAgICAgaW5wdXRzWyRpbnB1dC5hdHRyKCduYW1lJyldID0gY29uc3RydWN0b3JbdHlwZV0oe1xuICAgICAgICAgICAgICAgICQ6ICRpbnB1dFxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAvLyBncm91cCBieSBuYW1lIGF0dHJpYnV0ZVxuICAgICAgICAgICAgJGlucHV0LmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGlmKGluZGV4T2YobmFtZXMsICQodGhpcykuYXR0cignbmFtZScpKSA9PT0gLTEpIHtcbiAgICAgICAgICAgICAgICAgICAgbmFtZXMucHVzaCgkKHRoaXMpLmF0dHIoJ25hbWUnKSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGZvcmVhY2gobmFtZXMsIGZ1bmN0aW9uIChuYW1lKSB7XG4gICAgICAgICAgICAgICAgaW5wdXRzW25hbWVdID0gY29uc3RydWN0b3JbdHlwZV0oe1xuICAgICAgICAgICAgICAgICAgICAkOiAkc2VsZi5maW5kKCdpbnB1dFtuYW1lPVwiJyArIG5hbWUgKyAnXCJdJylcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfTtcblxuXG4gICAgaWYoJHNlbGYuaXMoJ2lucHV0LCBzZWxlY3QsIHRleHRhcmVhJykpIHtcbiAgICAgICAgaWYoJHNlbGYuaXMoJ2lucHV0W3R5cGU9XCJidXR0b25cIl0sIGJ1dHRvbiwgaW5wdXRbdHlwZT1cInN1Ym1pdFwiXScpKSB7XG4gICAgICAgICAgICBhZGRJbnB1dHNCYXNpYygnYnV0dG9uJywgJHNlbGYpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYoJHNlbGYuaXMoJ3RleHRhcmVhJykpIHtcbiAgICAgICAgICAgIGFkZElucHV0c0Jhc2ljKCd0ZXh0YXJlYScsICRzZWxmKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmKFxuICAgICAgICAgICAgJHNlbGYuaXMoJ2lucHV0W3R5cGU9XCJ0ZXh0XCJdJykgfHxcbiAgICAgICAgICAgICRzZWxmLmlzKCdpbnB1dCcpICYmICEkc2VsZi5hdHRyKCd0eXBlJylcbiAgICAgICAgKSB7XG4gICAgICAgICAgICBhZGRJbnB1dHNCYXNpYygndGV4dCcsICRzZWxmKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmKCRzZWxmLmlzKCdpbnB1dFt0eXBlPVwicGFzc3dvcmRcIl0nKSkge1xuICAgICAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ3Bhc3N3b3JkJywgJHNlbGYpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYoJHNlbGYuaXMoJ2lucHV0W3R5cGU9XCJlbWFpbFwiXScpKSB7XG4gICAgICAgICAgICBhZGRJbnB1dHNCYXNpYygnZW1haWwnLCAkc2VsZik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZigkc2VsZi5pcygnaW5wdXRbdHlwZT1cInVybFwiXScpKSB7XG4gICAgICAgICAgICBhZGRJbnB1dHNCYXNpYygndXJsJywgJHNlbGYpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYoJHNlbGYuaXMoJ2lucHV0W3R5cGU9XCJyYW5nZVwiXScpKSB7XG4gICAgICAgICAgICBhZGRJbnB1dHNCYXNpYygncmFuZ2UnLCAkc2VsZik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZigkc2VsZi5pcygnc2VsZWN0JykpIHtcbiAgICAgICAgICAgIGlmKCRzZWxmLmlzKCdbbXVsdGlwbGVdJykpIHtcbiAgICAgICAgICAgICAgICBhZGRJbnB1dHNCYXNpYygnc2VsZWN0W211bHRpcGxlXScsICRzZWxmKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGFkZElucHV0c0Jhc2ljKCdzZWxlY3QnLCAkc2VsZik7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZigkc2VsZi5pcygnaW5wdXRbdHlwZT1cImZpbGVcIl0nKSkge1xuICAgICAgICAgICAgaWYoJHNlbGYuaXMoJ1ttdWx0aXBsZV0nKSkge1xuICAgICAgICAgICAgICAgIGFkZElucHV0c0Jhc2ljKCdmaWxlW211bHRpcGxlXScsICRzZWxmKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIGFkZElucHV0c0Jhc2ljKCdmaWxlJywgJHNlbGYpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYoJHNlbGYuaXMoJ2lucHV0W3R5cGU9XCJoaWRkZW5cIl0nKSkge1xuICAgICAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ2hpZGRlbicsICRzZWxmKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIGlmKCRzZWxmLmlzKCdpbnB1dFt0eXBlPVwicmFkaW9cIl0nKSkge1xuICAgICAgICAgICAgYWRkSW5wdXRzR3JvdXAoJ3JhZGlvJywgJHNlbGYpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2UgaWYoJHNlbGYuaXMoJ2lucHV0W3R5cGU9XCJjaGVja2JveFwiXScpKSB7XG4gICAgICAgICAgICBhZGRJbnB1dHNHcm91cCgnY2hlY2tib3gnLCAkc2VsZik7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAvL2luIGFsbCBvdGhlciBjYXNlcyBkZWZhdWx0IHRvIGEgXCJ0ZXh0XCIgaW5wdXQgaW50ZXJmYWNlLlxuICAgICAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ3RleHQnLCAkc2VsZik7XG4gICAgICAgIH1cbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIGFkZElucHV0c0Jhc2ljKCdidXR0b24nLCAnaW5wdXRbdHlwZT1cImJ1dHRvblwiXSwgYnV0dG9uLCBpbnB1dFt0eXBlPVwic3VibWl0XCJdJyk7XG4gICAgICAgIGFkZElucHV0c0Jhc2ljKCd0ZXh0JywgJ2lucHV0W3R5cGU9XCJ0ZXh0XCJdJyk7XG4gICAgICAgIGFkZElucHV0c0Jhc2ljKCdwYXNzd29yZCcsICdpbnB1dFt0eXBlPVwicGFzc3dvcmRcIl0nKTtcbiAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ2VtYWlsJywgJ2lucHV0W3R5cGU9XCJlbWFpbFwiXScpO1xuICAgICAgICBhZGRJbnB1dHNCYXNpYygndXJsJywgJ2lucHV0W3R5cGU9XCJ1cmxcIl0nKTtcbiAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ3JhbmdlJywgJ2lucHV0W3R5cGU9XCJyYW5nZVwiXScpO1xuICAgICAgICBhZGRJbnB1dHNCYXNpYygndGV4dGFyZWEnLCAndGV4dGFyZWEnKTtcbiAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ3NlbGVjdCcsICdzZWxlY3Q6bm90KFttdWx0aXBsZV0pJyk7XG4gICAgICAgIGFkZElucHV0c0Jhc2ljKCdzZWxlY3RbbXVsdGlwbGVdJywgJ3NlbGVjdFttdWx0aXBsZV0nKTtcbiAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ2ZpbGUnLCAnaW5wdXRbdHlwZT1cImZpbGVcIl06bm90KFttdWx0aXBsZV0pJyk7XG4gICAgICAgIGFkZElucHV0c0Jhc2ljKCdmaWxlW211bHRpcGxlXScsICdpbnB1dFt0eXBlPVwiZmlsZVwiXVttdWx0aXBsZV0nKTtcbiAgICAgICAgYWRkSW5wdXRzQmFzaWMoJ2hpZGRlbicsICdpbnB1dFt0eXBlPVwiaGlkZGVuXCJdJyk7XG4gICAgICAgIGFkZElucHV0c0dyb3VwKCdyYWRpbycsICdpbnB1dFt0eXBlPVwicmFkaW9cIl0nKTtcbiAgICAgICAgYWRkSW5wdXRzR3JvdXAoJ2NoZWNrYm94JywgJ2lucHV0W3R5cGU9XCJjaGVja2JveFwiXScpO1xuICAgIH1cblxuICAgIHJldHVybiBpbnB1dHM7XG59O1xuXG4kLmZuLmlucHV0VmFsID0gZnVuY3Rpb24gKG5ld1ZhbHVlKSB7XG4gICAgdmFyICRzZWxmID0gJCh0aGlzKTtcblxuICAgIHZhciBpbnB1dHMgPSBidWlsZEZvcm1JbnB1dHMoeyAkOiAkc2VsZiB9KTtcblxuICAgIGlmKCRzZWxmLmlzKCdpbnB1dCwgdGV4dGFyZWEsIHNlbGVjdCcpKSB7XG4gICAgICAgIGlmKHR5cGVvZiBuZXdWYWx1ZSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHJldHVybiBpbnB1dHNbJHNlbGYuYXR0cignbmFtZScpXS5nZXQoKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGlucHV0c1skc2VsZi5hdHRyKCduYW1lJyldLnNldChuZXdWYWx1ZSk7XG4gICAgICAgICAgICByZXR1cm4gJHNlbGY7XG4gICAgICAgIH1cbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICAgIGlmKHR5cGVvZiBuZXdWYWx1ZSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHJldHVybiBjYWxsKGlucHV0cywgJ2dldCcpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgZm9yZWFjaChuZXdWYWx1ZSwgZnVuY3Rpb24gKHZhbHVlLCBpbnB1dE5hbWUpIHtcbiAgICAgICAgICAgICAgICBpbnB1dHNbaW5wdXROYW1lXS5zZXQodmFsdWUpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICByZXR1cm4gJHNlbGY7XG4gICAgICAgIH1cbiAgICB9XG59O1xuXG4kLmZuLmlucHV0T25DaGFuZ2UgPSBmdW5jdGlvbiAoY2FsbGJhY2spIHtcbiAgICB2YXIgJHNlbGYgPSAkKHRoaXMpO1xuICAgIHZhciBpbnB1dHMgPSBidWlsZEZvcm1JbnB1dHMoeyAkOiAkc2VsZiB9KTtcbiAgICBmb3JlYWNoKGlucHV0cywgZnVuY3Rpb24gKGlucHV0KSB7XG4gICAgICAgIGlucHV0LnN1YnNjcmliZSgnY2hhbmdlJywgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoZGF0YS5kb21FbGVtZW50LCBkYXRhLmUpO1xuICAgICAgICB9KTtcbiAgICB9KTtcbiAgICByZXR1cm4gJHNlbGY7XG59O1xuXG4kLmZuLmlucHV0RGlzYWJsZSA9IGZ1bmN0aW9uICgpIHtcbiAgICB2YXIgJHNlbGYgPSAkKHRoaXMpO1xuICAgIGNhbGwoYnVpbGRGb3JtSW5wdXRzKHsgJDogJHNlbGYgfSksICdkaXNhYmxlJyk7XG4gICAgcmV0dXJuICRzZWxmO1xufTtcblxuJC5mbi5pbnB1dEVuYWJsZSA9IGZ1bmN0aW9uICgpIHtcbiAgICB2YXIgJHNlbGYgPSAkKHRoaXMpO1xuICAgIGNhbGwoYnVpbGRGb3JtSW5wdXRzKHsgJDogJHNlbGYgfSksICdlbmFibGUnKTtcbiAgICByZXR1cm4gJHNlbGY7XG59O1xuXG4kLmZuLmlucHV0Q2xlYXIgPSBmdW5jdGlvbiAoKSB7XG4gICAgdmFyICRzZWxmID0gJCh0aGlzKTtcbiAgICBjYWxsKGJ1aWxkRm9ybUlucHV0cyh7ICQ6ICRzZWxmIH0pLCAnY2xlYXInKTtcbiAgICByZXR1cm4gJHNlbGY7XG59O1xuXG59KGpRdWVyeSkpO1xuXG4kLmZuLnJlcGVhdGVyVmFsID0gZnVuY3Rpb24gKCkge1xuICAgIHZhciBwYXJzZSA9IGZ1bmN0aW9uIChyYXcpIHtcbiAgICAgICAgdmFyIHBhcnNlZCA9IFtdO1xuXG4gICAgICAgIGZvcmVhY2gocmF3LCBmdW5jdGlvbiAodmFsLCBrZXkpIHtcbiAgICAgICAgICAgIHZhciBwYXJzZWRLZXkgPSBbXTtcbiAgICAgICAgICAgIGlmKGtleSAhPT0gXCJ1bmRlZmluZWRcIikge1xuICAgICAgICAgICAgICAgIHBhcnNlZEtleS5wdXNoKGtleS5tYXRjaCgvXlteXFxbXSovKVswXSk7XG4gICAgICAgICAgICAgICAgcGFyc2VkS2V5ID0gcGFyc2VkS2V5LmNvbmNhdChtYXAoXG4gICAgICAgICAgICAgICAgICAgIGtleS5tYXRjaCgvXFxbW15cXF1dKlxcXS9nKSxcbiAgICAgICAgICAgICAgICAgICAgZnVuY3Rpb24gKGJyYWNrZXRlZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGJyYWNrZXRlZC5yZXBsYWNlKC9bXFxbXFxdXS9nLCAnJyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICApKTtcblxuICAgICAgICAgICAgICAgIHBhcnNlZC5wdXNoKHtcbiAgICAgICAgICAgICAgICAgICAgdmFsOiB2YWwsXG4gICAgICAgICAgICAgICAgICAgIGtleTogcGFyc2VkS2V5XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHJldHVybiBwYXJzZWQ7XG4gICAgfTtcblxuICAgIHZhciBidWlsZCA9IGZ1bmN0aW9uIChwYXJzZWQpIHtcbiAgICAgICAgaWYoXG4gICAgICAgICAgICBwYXJzZWQubGVuZ3RoID09PSAxICYmXG4gICAgICAgICAgICAocGFyc2VkWzBdLmtleS5sZW5ndGggPT09IDAgfHwgcGFyc2VkWzBdLmtleS5sZW5ndGggPT09IDEgJiYgIXBhcnNlZFswXS5rZXlbMF0pXG4gICAgICAgICkge1xuICAgICAgICAgICAgcmV0dXJuIHBhcnNlZFswXS52YWw7XG4gICAgICAgIH1cblxuICAgICAgICBmb3JlYWNoKHBhcnNlZCwgZnVuY3Rpb24gKHApIHtcbiAgICAgICAgICAgIHAuaGVhZCA9IHAua2V5LnNoaWZ0KCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHZhciBncm91cGVkID0gKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBncm91cGVkID0ge307XG5cbiAgICAgICAgICAgIGZvcmVhY2gocGFyc2VkLCBmdW5jdGlvbiAocCkge1xuICAgICAgICAgICAgICAgIGlmKCFncm91cGVkW3AuaGVhZF0pIHtcbiAgICAgICAgICAgICAgICAgICAgZ3JvdXBlZFtwLmhlYWRdID0gW107XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGdyb3VwZWRbcC5oZWFkXS5wdXNoKHApO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHJldHVybiBncm91cGVkO1xuICAgICAgICB9KCkpO1xuXG4gICAgICAgIHZhciBidWlsdDtcblxuICAgICAgICBpZigvXlswLTldKyQvLnRlc3QocGFyc2VkWzBdLmhlYWQpKSB7XG4gICAgICAgICAgICBidWlsdCA9IFtdO1xuICAgICAgICAgICAgZm9yZWFjaChncm91cGVkLCBmdW5jdGlvbiAoZ3JvdXApIHtcbiAgICAgICAgICAgICAgICBidWlsdC5wdXNoKGJ1aWxkKGdyb3VwKSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGJ1aWx0ID0ge307XG4gICAgICAgICAgICBmb3JlYWNoKGdyb3VwZWQsIGZ1bmN0aW9uIChncm91cCwga2V5KSB7XG4gICAgICAgICAgICAgICAgYnVpbHRba2V5XSA9IGJ1aWxkKGdyb3VwKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIGJ1aWx0O1xuICAgIH07XG5cbiAgICByZXR1cm4gYnVpbGQocGFyc2UoJCh0aGlzKS5pbnB1dFZhbCgpKSk7XG59O1xuXG4kLmZuLnJlcGVhdGVyID0gZnVuY3Rpb24gKGZpZykge1xuICAgIGZpZyA9IGZpZyB8fCB7fTtcblxuICAgIHZhciBzZXRMaXN0O1xuICAgIHZhciBzZXRPcHRpb247XG4gICAgdmFyIGRlc3Rvcnk7XG5cbiAgICAkKHRoaXMpLmVhY2goZnVuY3Rpb24gKCkge1xuXG4gICAgICAgIHZhciAkc2VsZiA9ICQodGhpcyk7XG5cbiAgICAgICAgdmFyIHNob3cgPSBmaWcuc2hvdyB8fCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAkKHRoaXMpLnNob3coKTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgaGlkZSA9IGZpZy5oaWRlIHx8IGZ1bmN0aW9uIChyZW1vdmVFbGVtZW50KSB7XG4gICAgICAgICAgICByZW1vdmVFbGVtZW50KCk7XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyICRsaXN0ID0gJHNlbGYuZmluZCgnW2RhdGEtcmVwZWF0ZXItbGlzdF0nKS5maXJzdCgpO1xuXG4gICAgICAgIHZhciAkZmlsdGVyTmVzdGVkID0gZnVuY3Rpb24gKCRpdGVtcywgcmVwZWF0ZXJzKSB7XG4gICAgICAgICAgICByZXR1cm4gJGl0ZW1zLmZpbHRlcihmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlcGVhdGVycyA/XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuY2xvc2VzdChcbiAgICAgICAgICAgICAgICAgICAgICAgIHBsdWNrKHJlcGVhdGVycywgJ3NlbGVjdG9yJykuam9pbignLCcpXG4gICAgICAgICAgICAgICAgICAgICkubGVuZ3RoID09PSAwIDogdHJ1ZTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciAkaXRlbXMgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXR1cm4gJGZpbHRlck5lc3RlZCgkbGlzdC5maW5kKCdbZGF0YS1yZXBlYXRlci1pdGVtXScpLCBmaWcucmVwZWF0ZXJzKTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgJGl0ZW1UZW1wbGF0ZSA9ICRsaXN0LmZpbmQoJ1tkYXRhLXJlcGVhdGVyLWl0ZW1dJylcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC5maXJzdCgpLmNsb25lKCkuaGlkZSgpO1xuXG4gICAgICAgIHZhciAkZmlyc3REZWxldGVCdXR0b24gPSAkZmlsdGVyTmVzdGVkKFxuICAgICAgICAgICAgJGZpbHRlck5lc3RlZCgkKHRoaXMpLmZpbmQoJ1tkYXRhLXJlcGVhdGVyLWl0ZW1dJyksIGZpZy5yZXBlYXRlcnMpXG4gICAgICAgICAgICAuZmlyc3QoKS5maW5kKCdbZGF0YS1yZXBlYXRlci1kZWxldGVdJyksXG4gICAgICAgICAgICBmaWcucmVwZWF0ZXJzXG4gICAgICAgICk7XG5cbiAgICAgICAgaWYoZmlnLmlzRmlyc3RJdGVtVW5kZWxldGFibGUgJiYgJGZpcnN0RGVsZXRlQnV0dG9uKSB7XG4gICAgICAgICAgICAkZmlyc3REZWxldGVCdXR0b24ucmVtb3ZlKCk7XG4gICAgICAgIH1cblxuICAgICAgICB2YXIgZ2V0R3JvdXBOYW1lID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIGdyb3VwTmFtZSA9ICRsaXN0LmRhdGEoJ3JlcGVhdGVyLWxpc3QnKTtcbiAgICAgICAgICAgIHJldHVybiBmaWcuJHBhcmVudCA/XG4gICAgICAgICAgICAgICAgZmlnLiRwYXJlbnQuZGF0YSgnaXRlbS1uYW1lJykgKyAnWycgKyBncm91cE5hbWUgKyAnXScgOlxuICAgICAgICAgICAgICAgIGdyb3VwTmFtZTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgaW5pdE5lc3RlZCA9IGZ1bmN0aW9uICgkbGlzdEl0ZW1zKSB7XG4gICAgICAgICAgICBpZihmaWcucmVwZWF0ZXJzKSB7XG4gICAgICAgICAgICAgICAgJGxpc3RJdGVtcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyICRpdGVtID0gJCh0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgZm9yZWFjaChmaWcucmVwZWF0ZXJzLCBmdW5jdGlvbiAobmVzdGVkRmlnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAkaXRlbS5maW5kKG5lc3RlZEZpZy5zZWxlY3RvcikucmVwZWF0ZXIoZXh0ZW5kKFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5lc3RlZEZpZywgeyAkcGFyZW50OiAkaXRlbSB9XG4gICAgICAgICAgICAgICAgICAgICAgICApKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgICAgdmFyICRmb3JlYWNoUmVwZWF0ZXJJbkl0ZW0gPSBmdW5jdGlvbiAocmVwZWF0ZXJzLCAkaXRlbSwgY2IpIHtcbiAgICAgICAgICAgIGlmKHJlcGVhdGVycykge1xuICAgICAgICAgICAgICAgIGZvcmVhY2gocmVwZWF0ZXJzLCBmdW5jdGlvbiAobmVzdGVkRmlnKSB7XG4gICAgICAgICAgICAgICAgICAgIGNiLmNhbGwoJGl0ZW0uZmluZChuZXN0ZWRGaWcuc2VsZWN0b3IpWzBdLCBuZXN0ZWRGaWcpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBzZXRJbmRleGVzID0gZnVuY3Rpb24gKCRpdGVtcywgZ3JvdXBOYW1lLCByZXBlYXRlcnMpIHtcbiAgICAgICAgICAgICRpdGVtcy5lYWNoKGZ1bmN0aW9uIChpbmRleCkge1xuICAgICAgICAgICAgICAgIHZhciAkaXRlbSA9ICQodGhpcyk7XG4gICAgICAgICAgICAgICAgJGl0ZW0uZGF0YSgnaXRlbS1uYW1lJywgZ3JvdXBOYW1lICsgJ1snICsgaW5kZXggKyAnXScpO1xuICAgICAgICAgICAgICAgICRmaWx0ZXJOZXN0ZWQoJGl0ZW0uZmluZCgnW25hbWVdJyksIHJlcGVhdGVycylcbiAgICAgICAgICAgICAgICAuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciAkaW5wdXQgPSAkKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICAvLyBtYXRjaCBub24gZW1wdHkgYnJhY2tldHMgKGV4OiBcIltmb29dXCIpXG4gICAgICAgICAgICAgICAgICAgIHZhciBtYXRjaGVzID0gJGlucHV0LmF0dHIoJ25hbWUnKS5tYXRjaCgvXFxbW15cXF1dK1xcXS9nKTtcblxuICAgICAgICAgICAgICAgICAgICB2YXIgbmFtZSA9IG1hdGNoZXMgP1xuICAgICAgICAgICAgICAgICAgICAgICAgLy8gc3RyaXAgXCJbXCIgYW5kIFwiXVwiIGNoYXJhY3RlcnNcbiAgICAgICAgICAgICAgICAgICAgICAgIGxhc3QobWF0Y2hlcykucmVwbGFjZSgvXFxbfFxcXS9nLCAnJykgOlxuICAgICAgICAgICAgICAgICAgICAgICAgJGlucHV0LmF0dHIoJ25hbWUnKTtcblxuXG4gICAgICAgICAgICAgICAgICAgIHZhciBuZXdOYW1lID0gZ3JvdXBOYW1lICsgJ1snICsgaW5kZXggKyAnXVsnICsgbmFtZSArICddJyArXG4gICAgICAgICAgICAgICAgICAgICAgICAoJGlucHV0LmlzKCc6Y2hlY2tib3gnKSB8fCAkaW5wdXQuYXR0cignbXVsdGlwbGUnKSA/ICdbXScgOiAnJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgJGlucHV0LmF0dHIoJ25hbWUnLCBuZXdOYW1lKTtcblxuICAgICAgICAgICAgICAgICAgICAkZm9yZWFjaFJlcGVhdGVySW5JdGVtKHJlcGVhdGVycywgJGl0ZW0sIGZ1bmN0aW9uIChuZXN0ZWRGaWcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciAkcmVwZWF0ZXIgPSAkKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgc2V0SW5kZXhlcyhcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkZmlsdGVyTmVzdGVkKCRyZXBlYXRlci5maW5kKCdbZGF0YS1yZXBlYXRlci1pdGVtXScpLCBuZXN0ZWRGaWcucmVwZWF0ZXJzIHx8IFtdKSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBncm91cE5hbWUgKyAnWycgKyBpbmRleCArICddJyArXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJ1snICsgJHJlcGVhdGVyLmZpbmQoJ1tkYXRhLXJlcGVhdGVyLWxpc3RdJykuZmlyc3QoKS5kYXRhKCdyZXBlYXRlci1saXN0JykgKyAnXScsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbmVzdGVkRmlnLnJlcGVhdGVyc1xuICAgICAgICAgICAgICAgICAgICAgICAgKTtcbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgJGxpc3QuZmluZCgnaW5wdXRbbmFtZV1bY2hlY2tlZF0nKVxuICAgICAgICAgICAgICAgIC5yZW1vdmVBdHRyKCdjaGVja2VkJylcbiAgICAgICAgICAgICAgICAucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHNldEluZGV4ZXMoJGl0ZW1zKCksIGdldEdyb3VwTmFtZSgpLCBmaWcucmVwZWF0ZXJzKTtcbiAgICAgICAgaW5pdE5lc3RlZCgkaXRlbXMoKSk7XG4gICAgICAgIGlmKGZpZy5pbml0RW1wdHkpIHtcbiAgICAgICAgICAgICRpdGVtcygpLnJlbW92ZSgpO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYoZmlnLnJlYWR5KSB7XG4gICAgICAgICAgICBmaWcucmVhZHkoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHNldEluZGV4ZXMoJGl0ZW1zKCksIGdldEdyb3VwTmFtZSgpLCBmaWcucmVwZWF0ZXJzKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIGFwcGVuZEl0ZW0gPSAoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHNldEl0ZW1zVmFsdWVzID0gZnVuY3Rpb24gKCRpdGVtLCBkYXRhLCByZXBlYXRlcnMpIHtcbiAgICAgICAgICAgICAgICBpZihkYXRhIHx8IGZpZy5kZWZhdWx0VmFsdWVzKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciBpbnB1dE5hbWVzID0ge307XG4gICAgICAgICAgICAgICAgICAgICRmaWx0ZXJOZXN0ZWQoJGl0ZW0uZmluZCgnW25hbWVdJyksIHJlcGVhdGVycykuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIga2V5ID0gJCh0aGlzKS5hdHRyKCduYW1lJykubWF0Y2goL1xcWyhbXlxcXV0qKShcXF18XFxdXFxbXFxdKSQvKVsxXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlucHV0TmFtZXNba2V5XSA9ICQodGhpcykuYXR0cignbmFtZScpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbjtcbiAgICAgICAgICAgICAgICAgICAgJGl0ZW0uaW5wdXRWYWwobWFwKFxuICAgICAgICAgICAgICAgICAgICAgICAgZmlsdGVyKGRhdGEgfHwgZmlnLmRlZmF1bHRWYWx1ZXMsIGZ1bmN0aW9uICh2YWwsIG5hbWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gaW5wdXROYW1lc1tuYW1lXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pLFxuICAgICAgICAgICAgICAgICAgICAgICAgaWRlbnRpdHksXG4gICAgICAgICAgICAgICAgICAgICAgICBmdW5jdGlvbiAobmFtZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBpbnB1dE5hbWVzW25hbWVdO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICApKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAkZm9yZWFjaFJlcGVhdGVySW5JdGVtKHJlcGVhdGVycywgJGl0ZW0sIGZ1bmN0aW9uIChuZXN0ZWRGaWcpIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyICRyZXBlYXRlciA9ICQodGhpcyk7XG4gICAgICAgICAgICAgICAgICAgICRmaWx0ZXJOZXN0ZWQoXG4gICAgICAgICAgICAgICAgICAgICAgICAkcmVwZWF0ZXIuZmluZCgnW2RhdGEtcmVwZWF0ZXItaXRlbV0nKSxcbiAgICAgICAgICAgICAgICAgICAgICAgIG5lc3RlZEZpZy5yZXBlYXRlcnNcbiAgICAgICAgICAgICAgICAgICAgKVxuICAgICAgICAgICAgICAgICAgICAuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgZmllbGROYW1lID0gJHJlcGVhdGVyLmZpbmQoJ1tkYXRhLXJlcGVhdGVyLWxpc3RdJykuZGF0YSgncmVwZWF0ZXItbGlzdCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYoZGF0YSAmJiBkYXRhW2ZpZWxkTmFtZV0pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgJHRlbXBsYXRlID0gJCh0aGlzKS5jbG9uZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRyZXBlYXRlci5maW5kKCdbZGF0YS1yZXBlYXRlci1pdGVtXScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGZvcmVhY2goZGF0YVtmaWVsZE5hbWVdLCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgJGl0ZW0gPSAkdGVtcGxhdGUuY2xvbmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2V0SXRlbXNWYWx1ZXMoXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkaXRlbSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRhdGEsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXN0ZWRGaWcucmVwZWF0ZXJzIHx8IFtdXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICRyZXBlYXRlci5maW5kKCdbZGF0YS1yZXBlYXRlci1saXN0XScpLmFwcGVuZCgkaXRlbSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZXRJdGVtc1ZhbHVlcyhcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbmVzdGVkRmlnLmRlZmF1bHRWYWx1ZXMsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5lc3RlZEZpZy5yZXBlYXRlcnMgfHwgW11cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICApO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgfTtcblxuICAgICAgICAgICAgcmV0dXJuIGZ1bmN0aW9uICgkaXRlbSwgZGF0YSkge1xuICAgICAgICAgICAgICAgICRsaXN0LmFwcGVuZCgkaXRlbSk7XG4gICAgICAgICAgICAgICAgc2V0SW5kZXhlcygkaXRlbXMoKSwgZ2V0R3JvdXBOYW1lKCksIGZpZy5yZXBlYXRlcnMpO1xuICAgICAgICAgICAgICAgICRpdGVtLmZpbmQoJ1tuYW1lXScpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLmlucHV0Q2xlYXIoKTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBzZXRJdGVtc1ZhbHVlcygkaXRlbSwgZGF0YSB8fCBmaWcuZGVmYXVsdFZhbHVlcywgZmlnLnJlcGVhdGVycyk7XG4gICAgICAgICAgICB9O1xuICAgICAgICB9KCkpO1xuICAgICAgICB2YXIgdXBkYXRlT3B0aW9uICA9IChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgIHJldHVybiBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgICQuZWFjaChkYXRhLGZ1bmN0aW9uKG5hbWUsZGF0YV92YWx1ZSl7XG4gICAgICAgICAgICAgICAgICAgICRpdGVtVGVtcGxhdGUuZmluZCgnW25hbWU9XCInK25hbWUrJ1wiXScpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYoJCh0aGlzKS5pcygnc2VsZWN0Jykpe1xuICAgICAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5maW5kKCdvcHRpb246bm90KDpmaXJzdCknKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgdGhpc19zZWxlY3QgPSAkKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQuZWFjaChkYXRhX3ZhbHVlLCBmdW5jdGlvbihrZXksIGl0ZW0pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpc19zZWxlY3QuYXBwZW5kKCc8b3B0aW9uIHZhbHVlPVwiJytpdGVtLmlkKydcIj4nK2l0ZW0udmFsKyc8L29wdGlvbj4nKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgfSgpKTsgICAgXG4gICAgICAgIHZhciBhZGRJdGVtID0gZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgIHZhciAkaXRlbSA9ICRpdGVtVGVtcGxhdGUuY2xvbmUoKTtcbiAgICAgICAgICAgIGFwcGVuZEl0ZW0oJGl0ZW0sIGRhdGEpO1xuICAgICAgICAgICAgaWYoZmlnLnJlcGVhdGVycykge1xuICAgICAgICAgICAgICAgIGluaXROZXN0ZWQoJGl0ZW0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgc2hvdy5jYWxsKCRpdGVtLmdldCgwKSk7XG4gICAgICAgIH07XG4gICAgICAgIHZhciBhZGRPcHRpb24gPSBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgdXBkYXRlT3B0aW9uKGRhdGEpO1xuICAgICAgICAgICAgdmFyICRpdGVtID0gJGl0ZW1UZW1wbGF0ZS5jbG9uZSgpO1xuICAgICAgICAgICAgYXBwZW5kSXRlbSgkaXRlbSwgZGF0YSk7XG4gICAgICAgICAgICBpZihmaWcucmVwZWF0ZXJzKSB7XG4gICAgICAgICAgICAgICAgaW5pdE5lc3RlZCgkaXRlbSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBzaG93LmNhbGwoJGl0ZW0uZ2V0KDApKTtcbiAgICAgICAgfTtcblxuICAgICAgICBzZXRMaXN0ID0gZnVuY3Rpb24gKHJvd3MpIHtcbiAgICAgICAgICAgICRpdGVtcygpLnJlbW92ZSgpO1xuICAgICAgICAgICAgZm9yZWFjaChyb3dzLCBhZGRJdGVtKTtcbiAgICAgICAgfTtcbiAgICAgICAgc2V0T3B0aW9uID0gZnVuY3Rpb24gKHJvd3MpIHtcbiAgICAgICAgICAgICRpdGVtcygpLnJlbW92ZSgpO1xuICAgICAgICAgICAgZm9yZWFjaChyb3dzLCBhZGRPcHRpb24pO1xuICAgICAgICB9O1xuICAgICAgICBkZXN0b3J5ID0gZnVuY3Rpb24gKHJvd3MpIHtcbiAgICAgICAgICAgICRpdGVtcygpLnJlbW92ZSgpO1xuICAgICAgICAgICAgdmFyICRpdGVtID0gJGl0ZW1UZW1wbGF0ZS5jbG9uZSgpO1xuICAgICAgICAgICAgJCh0aGlzKS5maW5kKCdbZGF0YS1yZXBlYXRlci1saXN0XScpLmFwcGVuZCgkaXRlbS5zaG93KCkpO1xuICAgICAgICAgICAgJC5mbi5yZXBlYXRlcj17fTtcbiAgICAgICAgfTtcblxuICAgICAgICAkZmlsdGVyTmVzdGVkKCRzZWxmLmZpbmQoJ1tkYXRhLXJlcGVhdGVyLWNyZWF0ZV0nKSwgZmlnLnJlcGVhdGVycykuY2xpY2soZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgYWRkSXRlbSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICAkbGlzdC5vbignY2xpY2snLCAnW2RhdGEtcmVwZWF0ZXItZGVsZXRlXScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzZWxmID0gJCh0aGlzKS5jbG9zZXN0KCdbZGF0YS1yZXBlYXRlci1pdGVtXScpLmdldCgwKTtcbiAgICAgICAgICAgIGhpZGUuY2FsbChzZWxmLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgJChzZWxmKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBzZXRJbmRleGVzKCRpdGVtcygpLCBnZXRHcm91cE5hbWUoKSwgZmlnLnJlcGVhdGVycyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICB0aGlzLnNldExpc3QgPSBzZXRMaXN0O1xuICAgIHRoaXMuc2V0T3B0aW9uID0gc2V0T3B0aW9uO1xuICAgIHRoaXMuZGVzdG9yeSA9IGRlc3Rvcnk7XG5cbiAgICByZXR1cm4gdGhpcztcbn07XG5cbn0oalF1ZXJ5KSk7IiwidmFyIG9iamVjdEV4dGVuZCA9IGV4dGVuZDtcblxuLypcbiAgdmFyIG9iaiA9IHthOiAzLCBiOiA1fTtcbiAgZXh0ZW5kKG9iaiwge2E6IDQsIGM6IDh9KTsgLy8ge2E6IDQsIGI6IDUsIGM6IDh9XG4gIG9iajsgLy8ge2E6IDQsIGI6IDUsIGM6IDh9XG5cbiAgdmFyIG9iaiA9IHthOiAzLCBiOiA1fTtcbiAgZXh0ZW5kKHt9LCBvYmosIHthOiA0LCBjOiA4fSk7IC8vIHthOiA0LCBiOiA1LCBjOiA4fVxuICBvYmo7IC8vIHthOiAzLCBiOiA1fVxuXG4gIHZhciBhcnIgPSBbMSwgMiwgM107XG4gIHZhciBvYmogPSB7YTogMywgYjogNX07XG4gIGV4dGVuZChvYmosIHtjOiBhcnJ9KTsgLy8ge2E6IDMsIGI6IDUsIGM6IFsxLCAyLCAzXX1cbiAgYXJyLnB1c2goNCk7XG4gIG9iajsgLy8ge2E6IDMsIGI6IDUsIGM6IFsxLCAyLCAzLCA0XX1cblxuICB2YXIgYXJyID0gWzEsIDIsIDNdO1xuICB2YXIgb2JqID0ge2E6IDMsIGI6IDV9O1xuICBleHRlbmQodHJ1ZSwgb2JqLCB7YzogYXJyfSk7IC8vIHthOiAzLCBiOiA1LCBjOiBbMSwgMiwgM119XG4gIGFyci5wdXNoKDQpO1xuICBvYmo7IC8vIHthOiAzLCBiOiA1LCBjOiBbMSwgMiwgM119XG5cbiAgZXh0ZW5kKHthOiA0LCBiOiA1fSk7IC8vIHthOiA0LCBiOiA1fVxuICBleHRlbmQoe2E6IDQsIGI6IDV9LCAzKTsge2E6IDQsIGI6IDV9XG4gIGV4dGVuZCh7YTogNCwgYjogNX0sIHRydWUpOyB7YTogNCwgYjogNX1cbiAgZXh0ZW5kKCdoZWxsbycsIHthOiA0LCBiOiA1fSk7IC8vIHRocm93c1xuICBleHRlbmQoMywge2E6IDQsIGI6IDV9KTsgLy8gdGhyb3dzXG4qL1xuXG5mdW5jdGlvbiBleHRlbmQoLyogW2RlZXBdLCBvYmoxLCBvYmoyLCBbb2Jqbl0gKi8pIHtcbiAgdmFyIGFyZ3MgPSBbXS5zbGljZS5jYWxsKGFyZ3VtZW50cyk7XG4gIHZhciBkZWVwID0gZmFsc2U7XG4gIGlmICh0eXBlb2YgYXJnc1swXSA9PSAnYm9vbGVhbicpIHtcbiAgICBkZWVwID0gYXJncy5zaGlmdCgpO1xuICB9XG4gIHZhciByZXN1bHQgPSBhcmdzWzBdO1xuICBpZiAoaXNVbmV4dGVuZGFibGUocmVzdWx0KSkge1xuICAgIHRocm93IG5ldyBFcnJvcignZXh0ZW5kZWUgbXVzdCBiZSBhbiBvYmplY3QnKTtcbiAgfVxuICB2YXIgZXh0ZW5kZXJzID0gYXJncy5zbGljZSgxKTtcbiAgdmFyIGxlbiA9IGV4dGVuZGVycy5sZW5ndGg7XG4gIGZvciAodmFyIGkgPSAwOyBpIDwgbGVuOyBpKyspIHtcbiAgICB2YXIgZXh0ZW5kZXIgPSBleHRlbmRlcnNbaV07XG4gICAgZm9yICh2YXIga2V5IGluIGV4dGVuZGVyKSB7XG4gICAgICBpZiAoT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKGV4dGVuZGVyLCBrZXkpKSB7XG4gICAgICAgIHZhciB2YWx1ZSA9IGV4dGVuZGVyW2tleV07XG4gICAgICAgIGlmIChkZWVwICYmIGlzQ2xvbmVhYmxlKHZhbHVlKSkge1xuICAgICAgICAgIHZhciBiYXNlID0gQXJyYXkuaXNBcnJheSh2YWx1ZSkgPyBbXSA6IHt9O1xuICAgICAgICAgIHJlc3VsdFtrZXldID0gZXh0ZW5kKFxuICAgICAgICAgICAgdHJ1ZSxcbiAgICAgICAgICAgIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChyZXN1bHQsIGtleSkgJiYgIWlzVW5leHRlbmRhYmxlKHJlc3VsdFtrZXldKVxuICAgICAgICAgICAgICA/IHJlc3VsdFtrZXldXG4gICAgICAgICAgICAgIDogYmFzZSxcbiAgICAgICAgICAgIHZhbHVlXG4gICAgICAgICAgKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICByZXN1bHRba2V5XSA9IHZhbHVlO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICB9XG4gIHJldHVybiByZXN1bHQ7XG59XG5cbmZ1bmN0aW9uIGlzQ2xvbmVhYmxlKG9iaikge1xuICByZXR1cm4gQXJyYXkuaXNBcnJheShvYmopIHx8IHt9LnRvU3RyaW5nLmNhbGwob2JqKSA9PSAnW29iamVjdCBPYmplY3RdJztcbn1cblxuZnVuY3Rpb24gaXNVbmV4dGVuZGFibGUodmFsKSB7XG4gIHJldHVybiAhdmFsIHx8ICh0eXBlb2YgdmFsICE9ICdvYmplY3QnICYmIHR5cGVvZiB2YWwgIT0gJ2Z1bmN0aW9uJyk7XG59XG5cbmV4cG9ydCB7b2JqZWN0RXh0ZW5kIGFzIGRlZmF1bHR9O1xuIiwiaW1wb3J0ICRld0JLeSRqdXN0ZXh0ZW5kIGZyb20gXCJqdXN0LWV4dGVuZFwiO1xuXG5mdW5jdGlvbiAkcGFyY2VsJGludGVyb3BEZWZhdWx0KGEpIHtcbiAgcmV0dXJuIGEgJiYgYS5fX2VzTW9kdWxlID8gYS5kZWZhdWx0IDogYTtcbn1cblxuY2xhc3MgJDQwNDBhY2ZkODU4NDMzOGQkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOSB7XG4gICAgLy8gQWRkIGFuIGV2ZW50IGxpc3RlbmVyIGZvciBnaXZlbiBldmVudFxuICAgIG9uKGV2ZW50LCBmbikge1xuICAgICAgICB0aGlzLl9jYWxsYmFja3MgPSB0aGlzLl9jYWxsYmFja3MgfHwge1xuICAgICAgICB9O1xuICAgICAgICAvLyBDcmVhdGUgbmFtZXNwYWNlIGZvciB0aGlzIGV2ZW50XG4gICAgICAgIGlmICghdGhpcy5fY2FsbGJhY2tzW2V2ZW50XSkgdGhpcy5fY2FsbGJhY2tzW2V2ZW50XSA9IFtdO1xuICAgICAgICB0aGlzLl9jYWxsYmFja3NbZXZlbnRdLnB1c2goZm4pO1xuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9XG4gICAgZW1pdChldmVudCwgLi4uYXJncykge1xuICAgICAgICB0aGlzLl9jYWxsYmFja3MgPSB0aGlzLl9jYWxsYmFja3MgfHwge1xuICAgICAgICB9O1xuICAgICAgICBsZXQgY2FsbGJhY2tzID0gdGhpcy5fY2FsbGJhY2tzW2V2ZW50XTtcbiAgICAgICAgaWYgKGNhbGxiYWNrcykgZm9yIChsZXQgY2FsbGJhY2sgb2YgY2FsbGJhY2tzKWNhbGxiYWNrLmFwcGx5KHRoaXMsIGFyZ3MpO1xuICAgICAgICAvLyB0cmlnZ2VyIGEgY29ycmVzcG9uZGluZyBET00gZXZlbnRcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudCkgdGhpcy5lbGVtZW50LmRpc3BhdGNoRXZlbnQodGhpcy5tYWtlRXZlbnQoXCJkcm9wem9uZTpcIiArIGV2ZW50LCB7XG4gICAgICAgICAgICBhcmdzOiBhcmdzXG4gICAgICAgIH0pKTtcbiAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfVxuICAgIG1ha2VFdmVudChldmVudE5hbWUsIGRldGFpbCkge1xuICAgICAgICBsZXQgcGFyYW1zID0ge1xuICAgICAgICAgICAgYnViYmxlczogdHJ1ZSxcbiAgICAgICAgICAgIGNhbmNlbGFibGU6IHRydWUsXG4gICAgICAgICAgICBkZXRhaWw6IGRldGFpbFxuICAgICAgICB9O1xuICAgICAgICBpZiAodHlwZW9mIHdpbmRvdy5DdXN0b21FdmVudCA9PT0gXCJmdW5jdGlvblwiKSByZXR1cm4gbmV3IEN1c3RvbUV2ZW50KGV2ZW50TmFtZSwgcGFyYW1zKTtcbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAvLyBJRSAxMSBzdXBwb3J0XG4gICAgICAgICAgICAvLyBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9BUEkvQ3VzdG9tRXZlbnQvQ3VzdG9tRXZlbnRcbiAgICAgICAgICAgIHZhciBldnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudChcIkN1c3RvbUV2ZW50XCIpO1xuICAgICAgICAgICAgZXZ0LmluaXRDdXN0b21FdmVudChldmVudE5hbWUsIHBhcmFtcy5idWJibGVzLCBwYXJhbXMuY2FuY2VsYWJsZSwgcGFyYW1zLmRldGFpbCk7XG4gICAgICAgICAgICByZXR1cm4gZXZ0O1xuICAgICAgICB9XG4gICAgfVxuICAgIC8vIFJlbW92ZSBldmVudCBsaXN0ZW5lciBmb3IgZ2l2ZW4gZXZlbnQuIElmIGZuIGlzIG5vdCBwcm92aWRlZCwgYWxsIGV2ZW50XG4gICAgLy8gbGlzdGVuZXJzIGZvciB0aGF0IGV2ZW50IHdpbGwgYmUgcmVtb3ZlZC4gSWYgbmVpdGhlciBpcyBwcm92aWRlZCwgYWxsXG4gICAgLy8gZXZlbnQgbGlzdGVuZXJzIHdpbGwgYmUgcmVtb3ZlZC5cbiAgICBvZmYoZXZlbnQsIGZuKSB7XG4gICAgICAgIGlmICghdGhpcy5fY2FsbGJhY2tzIHx8IGFyZ3VtZW50cy5sZW5ndGggPT09IDApIHtcbiAgICAgICAgICAgIHRoaXMuX2NhbGxiYWNrcyA9IHtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICByZXR1cm4gdGhpcztcbiAgICAgICAgfVxuICAgICAgICAvLyBzcGVjaWZpYyBldmVudFxuICAgICAgICBsZXQgY2FsbGJhY2tzID0gdGhpcy5fY2FsbGJhY2tzW2V2ZW50XTtcbiAgICAgICAgaWYgKCFjYWxsYmFja3MpIHJldHVybiB0aGlzO1xuICAgICAgICAvLyByZW1vdmUgYWxsIGhhbmRsZXJzXG4gICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID09PSAxKSB7XG4gICAgICAgICAgICBkZWxldGUgdGhpcy5fY2FsbGJhY2tzW2V2ZW50XTtcbiAgICAgICAgICAgIHJldHVybiB0aGlzO1xuICAgICAgICB9XG4gICAgICAgIC8vIHJlbW92ZSBzcGVjaWZpYyBoYW5kbGVyXG4gICAgICAgIGZvcihsZXQgaSA9IDA7IGkgPCBjYWxsYmFja3MubGVuZ3RoOyBpKyspe1xuICAgICAgICAgICAgbGV0IGNhbGxiYWNrID0gY2FsbGJhY2tzW2ldO1xuICAgICAgICAgICAgaWYgKGNhbGxiYWNrID09PSBmbikge1xuICAgICAgICAgICAgICAgIGNhbGxiYWNrcy5zcGxpY2UoaSwgMSk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfVxufVxuXG5cblxudmFyICRmZDYwMzFmODhkY2UyZTMyJGV4cG9ydHMgPSB7fTtcbiRmZDYwMzFmODhkY2UyZTMyJGV4cG9ydHMgPSBcIjxkaXYgY2xhc3M9XFxcImR6LXByZXZpZXcgZHotZmlsZS1wcmV2aWV3XFxcIj5cXG4gIDxkaXYgY2xhc3M9XFxcImR6LWltYWdlXFxcIj48aW1nIGRhdGEtZHotdGh1bWJuYWlsPVxcXCJcXFwiPjwvZGl2PlxcbiAgPGRpdiBjbGFzcz1cXFwiZHotZGV0YWlsc1xcXCI+XFxuICAgIDxkaXYgY2xhc3M9XFxcImR6LXNpemVcXFwiPjxzcGFuIGRhdGEtZHotc2l6ZT1cXFwiXFxcIj48L3NwYW4+PC9kaXY+XFxuICAgIDxkaXYgY2xhc3M9XFxcImR6LWZpbGVuYW1lXFxcIj48c3BhbiBkYXRhLWR6LW5hbWU9XFxcIlxcXCI+PC9zcGFuPjwvZGl2PlxcbiAgPC9kaXY+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1wcm9ncmVzc1xcXCI+XFxuICAgIDxzcGFuIGNsYXNzPVxcXCJkei11cGxvYWRcXFwiIGRhdGEtZHotdXBsb2FkcHJvZ3Jlc3M9XFxcIlxcXCI+PC9zcGFuPlxcbiAgPC9kaXY+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1lcnJvci1tZXNzYWdlXFxcIj48c3BhbiBkYXRhLWR6LWVycm9ybWVzc2FnZT1cXFwiXFxcIj48L3NwYW4+PC9kaXY+XFxuICA8ZGl2IGNsYXNzPVxcXCJkei1zdWNjZXNzLW1hcmtcXFwiPlxcbiAgICA8c3ZnIHdpZHRoPVxcXCI1NFxcXCIgaGVpZ2h0PVxcXCI1NFxcXCIgdmlld0JveD1cXFwiMCAwIDU0IDU0XFxcIiBmaWxsPVxcXCJ3aGl0ZVxcXCIgeG1sbnM9XFxcImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXFxcIj5cXG4gICAgICA8cGF0aCBkPVxcXCJNMTAuMjA3MSAyOS43OTI5TDE0LjI5MjkgMjUuNzA3MUMxNC42ODM0IDI1LjMxNjYgMTUuMzE2NiAyNS4zMTY2IDE1LjcwNzEgMjUuNzA3MUwyMS4yOTI5IDMxLjI5MjlDMjEuNjgzNCAzMS42ODM0IDIyLjMxNjYgMzEuNjgzNCAyMi43MDcxIDMxLjI5MjlMMzguMjkyOSAxNS43MDcxQzM4LjY4MzQgMTUuMzE2NiAzOS4zMTY2IDE1LjMxNjYgMzkuNzA3MSAxNS43MDcxTDQzLjc5MjkgMTkuNzkyOUM0NC4xODM0IDIwLjE4MzQgNDQuMTgzNCAyMC44MTY2IDQzLjc5MjkgMjEuMjA3MUwyMi43MDcxIDQyLjI5MjlDMjIuMzE2NiA0Mi42ODM0IDIxLjY4MzQgNDIuNjgzNCAyMS4yOTI5IDQyLjI5MjlMMTAuMjA3MSAzMS4yMDcxQzkuODE2NTggMzAuODE2NiA5LjgxNjU4IDMwLjE4MzQgMTAuMjA3MSAyOS43OTI5WlxcXCI+PC9wYXRoPlxcbiAgICA8L3N2Zz5cXG4gIDwvZGl2PlxcbiAgPGRpdiBjbGFzcz1cXFwiZHotZXJyb3ItbWFya1xcXCI+XFxuICAgIDxzdmcgd2lkdGg9XFxcIjU0XFxcIiBoZWlnaHQ9XFxcIjU0XFxcIiB2aWV3Qm94PVxcXCIwIDAgNTQgNTRcXFwiIGZpbGw9XFxcIndoaXRlXFxcIiB4bWxucz1cXFwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcXFwiPlxcbiAgICAgIDxwYXRoIGQ9XFxcIk0yNi4yOTI5IDIwLjI5MjlMMTkuMjA3MSAxMy4yMDcxQzE4LjgxNjYgMTIuODE2NiAxOC4xODM0IDEyLjgxNjYgMTcuNzkyOSAxMy4yMDcxTDEzLjIwNzEgMTcuNzkyOUMxMi44MTY2IDE4LjE4MzQgMTIuODE2NiAxOC44MTY2IDEzLjIwNzEgMTkuMjA3MUwyMC4yOTI5IDI2LjI5MjlDMjAuNjgzNCAyNi42ODM0IDIwLjY4MzQgMjcuMzE2NiAyMC4yOTI5IDI3LjcwNzFMMTMuMjA3MSAzNC43OTI5QzEyLjgxNjYgMzUuMTgzNCAxMi44MTY2IDM1LjgxNjYgMTMuMjA3MSAzNi4yMDcxTDE3Ljc5MjkgNDAuNzkyOUMxOC4xODM0IDQxLjE4MzQgMTguODE2NiA0MS4xODM0IDE5LjIwNzEgNDAuNzkyOUwyNi4yOTI5IDMzLjcwNzFDMjYuNjgzNCAzMy4zMTY2IDI3LjMxNjYgMzMuMzE2NiAyNy43MDcxIDMzLjcwNzFMMzQuNzkyOSA0MC43OTI5QzM1LjE4MzQgNDEuMTgzNCAzNS44MTY2IDQxLjE4MzQgMzYuMjA3MSA0MC43OTI5TDQwLjc5MjkgMzYuMjA3MUM0MS4xODM0IDM1LjgxNjYgNDEuMTgzNCAzNS4xODM0IDQwLjc5MjkgMzQuNzkyOUwzMy43MDcxIDI3LjcwNzFDMzMuMzE2NiAyNy4zMTY2IDMzLjMxNjYgMjYuNjgzNCAzMy43MDcxIDI2LjI5MjlMNDAuNzkyOSAxOS4yMDcxQzQxLjE4MzQgMTguODE2NiA0MS4xODM0IDE4LjE4MzQgNDAuNzkyOSAxNy43OTI5TDM2LjIwNzEgMTMuMjA3MUMzNS44MTY2IDEyLjgxNjYgMzUuMTgzNCAxMi44MTY2IDM0Ljc5MjkgMTMuMjA3MUwyNy43MDcxIDIwLjI5MjlDMjcuMzE2NiAyMC42ODM0IDI2LjY4MzQgMjAuNjgzNCAyNi4yOTI5IDIwLjI5MjlaXFxcIj48L3BhdGg+XFxuICAgIDwvc3ZnPlxcbiAgPC9kaXY+XFxuPC9kaXY+XFxuXCI7XG5cblxubGV0ICQ0Y2EzNjcxODI3NzZmODBiJHZhciRkZWZhdWx0T3B0aW9ucyA9IHtcbiAgICAvKipcbiAgICogSGFzIHRvIGJlIHNwZWNpZmllZCBvbiBlbGVtZW50cyBvdGhlciB0aGFuIGZvcm0gKG9yIHdoZW4gdGhlIGZvcm0gZG9lc24ndFxuICAgKiBoYXZlIGFuIGBhY3Rpb25gIGF0dHJpYnV0ZSkuXG4gICAqXG4gICAqIFlvdSBjYW4gYWxzbyBwcm92aWRlIGEgZnVuY3Rpb24gdGhhdCB3aWxsIGJlIGNhbGxlZCB3aXRoIGBmaWxlc2AgYW5kXG4gICAqIGBkYXRhQmxvY2tzYCAgYW5kIG11c3QgcmV0dXJuIHRoZSB1cmwgYXMgc3RyaW5nLlxuICAgKi8gdXJsOiBudWxsLFxuICAgIC8qKlxuICAgKiBDYW4gYmUgY2hhbmdlZCB0byBgXCJwdXRcImAgaWYgbmVjZXNzYXJ5LiBZb3UgY2FuIGFsc28gcHJvdmlkZSBhIGZ1bmN0aW9uXG4gICAqIHRoYXQgd2lsbCBiZSBjYWxsZWQgd2l0aCBgZmlsZXNgIGFuZCBtdXN0IHJldHVybiB0aGUgbWV0aG9kIChzaW5jZSBgdjMuMTIuMGApLlxuICAgKi8gbWV0aG9kOiBcInBvc3RcIixcbiAgICAvKipcbiAgICogV2lsbCBiZSBzZXQgb24gdGhlIFhIUmVxdWVzdC5cbiAgICovIHdpdGhDcmVkZW50aWFsczogZmFsc2UsXG4gICAgLyoqXG4gICAqIFRoZSB0aW1lb3V0IGZvciB0aGUgWEhSIHJlcXVlc3RzIGluIG1pbGxpc2Vjb25kcyAoc2luY2UgYHY0LjQuMGApLlxuICAgKiBJZiBzZXQgdG8gbnVsbCBvciAwLCBubyB0aW1lb3V0IGlzIGdvaW5nIHRvIGJlIHNldC5cbiAgICovIHRpbWVvdXQ6IG51bGwsXG4gICAgLyoqXG4gICAqIEhvdyBtYW55IGZpbGUgdXBsb2FkcyB0byBwcm9jZXNzIGluIHBhcmFsbGVsIChTZWUgdGhlXG4gICAqIEVucXVldWluZyBmaWxlIHVwbG9hZHMgZG9jdW1lbnRhdGlvbiBzZWN0aW9uIGZvciBtb3JlIGluZm8pXG4gICAqLyBwYXJhbGxlbFVwbG9hZHM6IDIsXG4gICAgLyoqXG4gICAqIFdoZXRoZXIgdG8gc2VuZCBtdWx0aXBsZSBmaWxlcyBpbiBvbmUgcmVxdWVzdC4gSWZcbiAgICogdGhpcyBpdCBzZXQgdG8gdHJ1ZSwgdGhlbiB0aGUgZmFsbGJhY2sgZmlsZSBpbnB1dCBlbGVtZW50IHdpbGxcbiAgICogaGF2ZSB0aGUgYG11bHRpcGxlYCBhdHRyaWJ1dGUgYXMgd2VsbC4gVGhpcyBvcHRpb24gd2lsbFxuICAgKiBhbHNvIHRyaWdnZXIgYWRkaXRpb25hbCBldmVudHMgKGxpa2UgYHByb2Nlc3NpbmdtdWx0aXBsZWApLiBTZWUgdGhlIGV2ZW50c1xuICAgKiBkb2N1bWVudGF0aW9uIHNlY3Rpb24gZm9yIG1vcmUgaW5mb3JtYXRpb24uXG4gICAqLyB1cGxvYWRNdWx0aXBsZTogZmFsc2UsXG4gICAgLyoqXG4gICAqIFdoZXRoZXIgeW91IHdhbnQgZmlsZXMgdG8gYmUgdXBsb2FkZWQgaW4gY2h1bmtzIHRvIHlvdXIgc2VydmVyLiBUaGlzIGNhbid0IGJlXG4gICAqIHVzZWQgaW4gY29tYmluYXRpb24gd2l0aCBgdXBsb2FkTXVsdGlwbGVgLlxuICAgKlxuICAgKiBTZWUgW2NodW5rc1VwbG9hZGVkXSgjY29uZmlnLWNodW5rc1VwbG9hZGVkKSBmb3IgdGhlIGNhbGxiYWNrIHRvIGZpbmFsaXNlIGFuIHVwbG9hZC5cbiAgICovIGNodW5raW5nOiBmYWxzZSxcbiAgICAvKipcbiAgICogSWYgYGNodW5raW5nYCBpcyBlbmFibGVkLCB0aGlzIGRlZmluZXMgd2hldGhlciAqKmV2ZXJ5KiogZmlsZSBzaG91bGQgYmUgY2h1bmtlZCxcbiAgICogZXZlbiBpZiB0aGUgZmlsZSBzaXplIGlzIGJlbG93IGNodW5rU2l6ZS4gVGhpcyBtZWFucywgdGhhdCB0aGUgYWRkaXRpb25hbCBjaHVua1xuICAgKiBmb3JtIGRhdGEgd2lsbCBiZSBzdWJtaXR0ZWQgYW5kIHRoZSBgY2h1bmtzVXBsb2FkZWRgIGNhbGxiYWNrIHdpbGwgYmUgaW52b2tlZC5cbiAgICovIGZvcmNlQ2h1bmtpbmc6IGZhbHNlLFxuICAgIC8qKlxuICAgKiBJZiBgY2h1bmtpbmdgIGlzIGB0cnVlYCwgdGhlbiB0aGlzIGRlZmluZXMgdGhlIGNodW5rIHNpemUgaW4gYnl0ZXMuXG4gICAqLyBjaHVua1NpemU6IDIwOTcxNTIsXG4gICAgLyoqXG4gICAqIElmIGB0cnVlYCwgdGhlIGluZGl2aWR1YWwgY2h1bmtzIG9mIGEgZmlsZSBhcmUgYmVpbmcgdXBsb2FkZWQgc2ltdWx0YW5lb3VzbHkuXG4gICAqLyBwYXJhbGxlbENodW5rVXBsb2FkczogZmFsc2UsXG4gICAgLyoqXG4gICAqIFdoZXRoZXIgYSBjaHVuayBzaG91bGQgYmUgcmV0cmllZCBpZiBpdCBmYWlscy5cbiAgICovIHJldHJ5Q2h1bmtzOiBmYWxzZSxcbiAgICAvKipcbiAgICogSWYgYHJldHJ5Q2h1bmtzYCBpcyB0cnVlLCBob3cgbWFueSB0aW1lcyBzaG91bGQgaXQgYmUgcmV0cmllZC5cbiAgICovIHJldHJ5Q2h1bmtzTGltaXQ6IDMsXG4gICAgLyoqXG4gICAqIFRoZSBtYXhpbXVtIGZpbGVzaXplIChpbiBNaUIpIHRoYXQgaXMgYWxsb3dlZCB0byBiZSB1cGxvYWRlZC5cbiAgICovIG1heEZpbGVzaXplOiAyNTYsXG4gICAgLyoqXG4gICAqIFRoZSBuYW1lIG9mIHRoZSBmaWxlIHBhcmFtIHRoYXQgZ2V0cyB0cmFuc2ZlcnJlZC5cbiAgICogKipOT1RFKio6IElmIHlvdSBoYXZlIHRoZSBvcHRpb24gIGB1cGxvYWRNdWx0aXBsZWAgc2V0IHRvIGB0cnVlYCwgdGhlblxuICAgKiBEcm9wem9uZSB3aWxsIGFwcGVuZCBgW11gIHRvIHRoZSBuYW1lLlxuICAgKi8gcGFyYW1OYW1lOiBcImZpbGVcIixcbiAgICAvKipcbiAgICogV2hldGhlciB0aHVtYm5haWxzIGZvciBpbWFnZXMgc2hvdWxkIGJlIGdlbmVyYXRlZFxuICAgKi8gY3JlYXRlSW1hZ2VUaHVtYm5haWxzOiB0cnVlLFxuICAgIC8qKlxuICAgKiBJbiBNQi4gV2hlbiB0aGUgZmlsZW5hbWUgZXhjZWVkcyB0aGlzIGxpbWl0LCB0aGUgdGh1bWJuYWlsIHdpbGwgbm90IGJlIGdlbmVyYXRlZC5cbiAgICovIG1heFRodW1ibmFpbEZpbGVzaXplOiAxMCxcbiAgICAvKipcbiAgICogSWYgYG51bGxgLCB0aGUgcmF0aW8gb2YgdGhlIGltYWdlIHdpbGwgYmUgdXNlZCB0byBjYWxjdWxhdGUgaXQuXG4gICAqLyB0aHVtYm5haWxXaWR0aDogMTIwLFxuICAgIC8qKlxuICAgKiBUaGUgc2FtZSBhcyBgdGh1bWJuYWlsV2lkdGhgLiBJZiBib3RoIGFyZSBudWxsLCBpbWFnZXMgd2lsbCBub3QgYmUgcmVzaXplZC5cbiAgICovIHRodW1ibmFpbEhlaWdodDogMTIwLFxuICAgIC8qKlxuICAgKiBIb3cgdGhlIGltYWdlcyBzaG91bGQgYmUgc2NhbGVkIGRvd24gaW4gY2FzZSBib3RoLCBgdGh1bWJuYWlsV2lkdGhgIGFuZCBgdGh1bWJuYWlsSGVpZ2h0YCBhcmUgcHJvdmlkZWQuXG4gICAqIENhbiBiZSBlaXRoZXIgYGNvbnRhaW5gIG9yIGBjcm9wYC5cbiAgICovIHRodW1ibmFpbE1ldGhvZDogXCJjcm9wXCIsXG4gICAgLyoqXG4gICAqIElmIHNldCwgaW1hZ2VzIHdpbGwgYmUgcmVzaXplZCB0byB0aGVzZSBkaW1lbnNpb25zIGJlZm9yZSBiZWluZyAqKnVwbG9hZGVkKiouXG4gICAqIElmIG9ubHkgb25lLCBgcmVzaXplV2lkdGhgICoqb3IqKiBgcmVzaXplSGVpZ2h0YCBpcyBwcm92aWRlZCwgdGhlIG9yaWdpbmFsIGFzcGVjdFxuICAgKiByYXRpbyBvZiB0aGUgZmlsZSB3aWxsIGJlIHByZXNlcnZlZC5cbiAgICpcbiAgICogVGhlIGBvcHRpb25zLnRyYW5zZm9ybUZpbGVgIGZ1bmN0aW9uIHVzZXMgdGhlc2Ugb3B0aW9ucywgc28gaWYgdGhlIGB0cmFuc2Zvcm1GaWxlYCBmdW5jdGlvblxuICAgKiBpcyBvdmVycmlkZGVuLCB0aGVzZSBvcHRpb25zIGRvbid0IGRvIGFueXRoaW5nLlxuICAgKi8gcmVzaXplV2lkdGg6IG51bGwsXG4gICAgLyoqXG4gICAqIFNlZSBgcmVzaXplV2lkdGhgLlxuICAgKi8gcmVzaXplSGVpZ2h0OiBudWxsLFxuICAgIC8qKlxuICAgKiBUaGUgbWltZSB0eXBlIG9mIHRoZSByZXNpemVkIGltYWdlIChiZWZvcmUgaXQgZ2V0cyB1cGxvYWRlZCB0byB0aGUgc2VydmVyKS5cbiAgICogSWYgYG51bGxgIHRoZSBvcmlnaW5hbCBtaW1lIHR5cGUgd2lsbCBiZSB1c2VkLiBUbyBmb3JjZSBqcGVnLCBmb3IgZXhhbXBsZSwgdXNlIGBpbWFnZS9qcGVnYC5cbiAgICogU2VlIGByZXNpemVXaWR0aGAgZm9yIG1vcmUgaW5mb3JtYXRpb24uXG4gICAqLyByZXNpemVNaW1lVHlwZTogbnVsbCxcbiAgICAvKipcbiAgICogVGhlIHF1YWxpdHkgb2YgdGhlIHJlc2l6ZWQgaW1hZ2VzLiBTZWUgYHJlc2l6ZVdpZHRoYC5cbiAgICovIHJlc2l6ZVF1YWxpdHk6IDAuOCxcbiAgICAvKipcbiAgICogSG93IHRoZSBpbWFnZXMgc2hvdWxkIGJlIHNjYWxlZCBkb3duIGluIGNhc2UgYm90aCwgYHJlc2l6ZVdpZHRoYCBhbmQgYHJlc2l6ZUhlaWdodGAgYXJlIHByb3ZpZGVkLlxuICAgKiBDYW4gYmUgZWl0aGVyIGBjb250YWluYCBvciBgY3JvcGAuXG4gICAqLyByZXNpemVNZXRob2Q6IFwiY29udGFpblwiLFxuICAgIC8qKlxuICAgKiBUaGUgYmFzZSB0aGF0IGlzIHVzZWQgdG8gY2FsY3VsYXRlIHRoZSAqKmRpc3BsYXllZCoqIGZpbGVzaXplLiBZb3UgY2FuXG4gICAqIGNoYW5nZSB0aGlzIHRvIDEwMjQgaWYgeW91IHdvdWxkIHJhdGhlciBkaXNwbGF5IGtpYmlieXRlcywgbWViaWJ5dGVzLFxuICAgKiBldGMuLi4gMTAyNCBpcyB0ZWNobmljYWxseSBpbmNvcnJlY3QsIGJlY2F1c2UgYDEwMjQgYnl0ZXNgIGFyZSBgMSBraWJpYnl0ZWBcbiAgICogbm90IGAxIGtpbG9ieXRlYC4gWW91IGNhbiBjaGFuZ2UgdGhpcyB0byBgMTAyNGAgaWYgeW91IGRvbid0IGNhcmUgYWJvdXRcbiAgICogdmFsaWRpdHkuXG4gICAqLyBmaWxlc2l6ZUJhc2U6IDEwMDAsXG4gICAgLyoqXG4gICAqIElmIG5vdCBgbnVsbGAgZGVmaW5lcyBob3cgbWFueSBmaWxlcyB0aGlzIERyb3B6b25lIGhhbmRsZXMuIElmIGl0IGV4Y2VlZHMsXG4gICAqIHRoZSBldmVudCBgbWF4ZmlsZXNleGNlZWRlZGAgd2lsbCBiZSBjYWxsZWQuIFRoZSBkcm9wem9uZSBlbGVtZW50IGdldHMgdGhlXG4gICAqIGNsYXNzIGBkei1tYXgtZmlsZXMtcmVhY2hlZGAgYWNjb3JkaW5nbHkgc28geW91IGNhbiBwcm92aWRlIHZpc3VhbFxuICAgKiBmZWVkYmFjay5cbiAgICovIG1heEZpbGVzOiBudWxsLFxuICAgIC8qKlxuICAgKiBBbiBvcHRpb25hbCBvYmplY3QgdG8gc2VuZCBhZGRpdGlvbmFsIGhlYWRlcnMgdG8gdGhlIHNlcnZlci4gRWc6XG4gICAqIGB7IFwiTXktQXdlc29tZS1IZWFkZXJcIjogXCJoZWFkZXIgdmFsdWVcIiB9YFxuICAgKi8gaGVhZGVyczogbnVsbCxcbiAgICAvKipcbiAgICogU2hvdWxkIHRoZSBkZWZhdWx0IGhlYWRlcnMgYmUgc2V0IG9yIG5vdD9cbiAgICogQWNjZXB0OiBhcHBsaWNhdGlvbi9qc29uIDwtIGZvciByZXF1ZXN0aW5nIGpzb24gcmVzcG9uc2VcbiAgICogQ2FjaGUtQ29udHJvbDogbm8tY2FjaGUgPC0gUmVxdWVzdCBzaG91bGRudCBiZSBjYWNoZWRcbiAgICogWC1SZXF1ZXN0ZWQtV2l0aDogWE1MSHR0cFJlcXVlc3QgPC0gV2Ugc2VudCB0aGUgcmVxdWVzdCB2aWEgWE1MSHR0cFJlcXVlc3RcbiAgICovIGRlZmF1bHRIZWFkZXJzOiB0cnVlLFxuICAgIC8qKlxuICAgKiBJZiBgdHJ1ZWAsIHRoZSBkcm9wem9uZSBlbGVtZW50IGl0c2VsZiB3aWxsIGJlIGNsaWNrYWJsZSwgaWYgYGZhbHNlYFxuICAgKiBub3RoaW5nIHdpbGwgYmUgY2xpY2thYmxlLlxuICAgKlxuICAgKiBZb3UgY2FuIGFsc28gcGFzcyBhbiBIVE1MIGVsZW1lbnQsIGEgQ1NTIHNlbGVjdG9yIChmb3IgbXVsdGlwbGUgZWxlbWVudHMpXG4gICAqIG9yIGFuIGFycmF5IG9mIHRob3NlLiBJbiB0aGF0IGNhc2UsIGFsbCBvZiB0aG9zZSBlbGVtZW50cyB3aWxsIHRyaWdnZXIgYW5cbiAgICogdXBsb2FkIHdoZW4gY2xpY2tlZC5cbiAgICovIGNsaWNrYWJsZTogdHJ1ZSxcbiAgICAvKipcbiAgICogV2hldGhlciBoaWRkZW4gZmlsZXMgaW4gZGlyZWN0b3JpZXMgc2hvdWxkIGJlIGlnbm9yZWQuXG4gICAqLyBpZ25vcmVIaWRkZW5GaWxlczogdHJ1ZSxcbiAgICAvKipcbiAgICogVGhlIGRlZmF1bHQgaW1wbGVtZW50YXRpb24gb2YgYGFjY2VwdGAgY2hlY2tzIHRoZSBmaWxlJ3MgbWltZSB0eXBlIG9yXG4gICAqIGV4dGVuc2lvbiBhZ2FpbnN0IHRoaXMgbGlzdC4gVGhpcyBpcyBhIGNvbW1hIHNlcGFyYXRlZCBsaXN0IG9mIG1pbWVcbiAgICogdHlwZXMgb3IgZmlsZSBleHRlbnNpb25zLlxuICAgKlxuICAgKiBFZy46IGBpbWFnZS8qLGFwcGxpY2F0aW9uL3BkZiwucHNkYFxuICAgKlxuICAgKiBJZiB0aGUgRHJvcHpvbmUgaXMgYGNsaWNrYWJsZWAgdGhpcyBvcHRpb24gd2lsbCBhbHNvIGJlIHVzZWQgYXNcbiAgICogW2BhY2NlcHRgXShodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL0hUTUwvRWxlbWVudC9pbnB1dCNhdHRyLWFjY2VwdClcbiAgICogcGFyYW1ldGVyIG9uIHRoZSBoaWRkZW4gZmlsZSBpbnB1dCBhcyB3ZWxsLlxuICAgKi8gYWNjZXB0ZWRGaWxlczogbnVsbCxcbiAgICAvKipcbiAgICogKipEZXByZWNhdGVkISoqXG4gICAqIFVzZSBhY2NlcHRlZEZpbGVzIGluc3RlYWQuXG4gICAqLyBhY2NlcHRlZE1pbWVUeXBlczogbnVsbCxcbiAgICAvKipcbiAgICogSWYgZmFsc2UsIGZpbGVzIHdpbGwgYmUgYWRkZWQgdG8gdGhlIHF1ZXVlIGJ1dCB0aGUgcXVldWUgd2lsbCBub3QgYmVcbiAgICogcHJvY2Vzc2VkIGF1dG9tYXRpY2FsbHkuXG4gICAqIFRoaXMgY2FuIGJlIHVzZWZ1bCBpZiB5b3UgbmVlZCBzb21lIGFkZGl0aW9uYWwgdXNlciBpbnB1dCBiZWZvcmUgc2VuZGluZ1xuICAgKiBmaWxlcyAob3IgaWYgeW91IHdhbnQgd2FudCBhbGwgZmlsZXMgc2VudCBhdCBvbmNlKS5cbiAgICogSWYgeW91J3JlIHJlYWR5IHRvIHNlbmQgdGhlIGZpbGUgc2ltcGx5IGNhbGwgYG15RHJvcHpvbmUucHJvY2Vzc1F1ZXVlKClgLlxuICAgKlxuICAgKiBTZWUgdGhlIFtlbnF1ZXVpbmcgZmlsZSB1cGxvYWRzXSgjZW5xdWV1aW5nLWZpbGUtdXBsb2FkcykgZG9jdW1lbnRhdGlvblxuICAgKiBzZWN0aW9uIGZvciBtb3JlIGluZm9ybWF0aW9uLlxuICAgKi8gYXV0b1Byb2Nlc3NRdWV1ZTogdHJ1ZSxcbiAgICAvKipcbiAgICogSWYgZmFsc2UsIGZpbGVzIGFkZGVkIHRvIHRoZSBkcm9wem9uZSB3aWxsIG5vdCBiZSBxdWV1ZWQgYnkgZGVmYXVsdC5cbiAgICogWW91J2xsIGhhdmUgdG8gY2FsbCBgZW5xdWV1ZUZpbGUoZmlsZSlgIG1hbnVhbGx5LlxuICAgKi8gYXV0b1F1ZXVlOiB0cnVlLFxuICAgIC8qKlxuICAgKiBJZiBgdHJ1ZWAsIHRoaXMgd2lsbCBhZGQgYSBsaW5rIHRvIGV2ZXJ5IGZpbGUgcHJldmlldyB0byByZW1vdmUgb3IgY2FuY2VsIChpZlxuICAgKiBhbHJlYWR5IHVwbG9hZGluZykgdGhlIGZpbGUuIFRoZSBgZGljdENhbmNlbFVwbG9hZGAsIGBkaWN0Q2FuY2VsVXBsb2FkQ29uZmlybWF0aW9uYFxuICAgKiBhbmQgYGRpY3RSZW1vdmVGaWxlYCBvcHRpb25zIGFyZSB1c2VkIGZvciB0aGUgd29yZGluZy5cbiAgICovIGFkZFJlbW92ZUxpbmtzOiBmYWxzZSxcbiAgICAvKipcbiAgICogRGVmaW5lcyB3aGVyZSB0byBkaXNwbGF5IHRoZSBmaWxlIHByZXZpZXdzIOKAkyBpZiBgbnVsbGAgdGhlXG4gICAqIERyb3B6b25lIGVsZW1lbnQgaXRzZWxmIGlzIHVzZWQuIENhbiBiZSBhIHBsYWluIGBIVE1MRWxlbWVudGAgb3IgYSBDU1NcbiAgICogc2VsZWN0b3IuIFRoZSBlbGVtZW50IHNob3VsZCBoYXZlIHRoZSBgZHJvcHpvbmUtcHJldmlld3NgIGNsYXNzIHNvXG4gICAqIHRoZSBwcmV2aWV3cyBhcmUgZGlzcGxheWVkIHByb3Blcmx5LlxuICAgKi8gcHJldmlld3NDb250YWluZXI6IG51bGwsXG4gICAgLyoqXG4gICAqIFNldCB0aGlzIHRvIGB0cnVlYCBpZiB5b3UgZG9uJ3Qgd2FudCBwcmV2aWV3cyB0byBiZSBzaG93bi5cbiAgICovIGRpc2FibGVQcmV2aWV3czogZmFsc2UsXG4gICAgLyoqXG4gICAqIFRoaXMgaXMgdGhlIGVsZW1lbnQgdGhlIGhpZGRlbiBpbnB1dCBmaWVsZCAod2hpY2ggaXMgdXNlZCB3aGVuIGNsaWNraW5nIG9uIHRoZVxuICAgKiBkcm9wem9uZSB0byB0cmlnZ2VyIGZpbGUgc2VsZWN0aW9uKSB3aWxsIGJlIGFwcGVuZGVkIHRvLiBUaGlzIG1pZ2h0XG4gICAqIGJlIGltcG9ydGFudCBpbiBjYXNlIHlvdSB1c2UgZnJhbWV3b3JrcyB0byBzd2l0Y2ggdGhlIGNvbnRlbnQgb2YgeW91ciBwYWdlLlxuICAgKlxuICAgKiBDYW4gYmUgYSBzZWxlY3RvciBzdHJpbmcsIG9yIGFuIGVsZW1lbnQgZGlyZWN0bHkuXG4gICAqLyBoaWRkZW5JbnB1dENvbnRhaW5lcjogXCJib2R5XCIsXG4gICAgLyoqXG4gICAqIElmIG51bGwsIG5vIGNhcHR1cmUgdHlwZSB3aWxsIGJlIHNwZWNpZmllZFxuICAgKiBJZiBjYW1lcmEsIG1vYmlsZSBkZXZpY2VzIHdpbGwgc2tpcCB0aGUgZmlsZSBzZWxlY3Rpb24gYW5kIGNob29zZSBjYW1lcmFcbiAgICogSWYgbWljcm9waG9uZSwgbW9iaWxlIGRldmljZXMgd2lsbCBza2lwIHRoZSBmaWxlIHNlbGVjdGlvbiBhbmQgY2hvb3NlIHRoZSBtaWNyb3Bob25lXG4gICAqIElmIGNhbWNvcmRlciwgbW9iaWxlIGRldmljZXMgd2lsbCBza2lwIHRoZSBmaWxlIHNlbGVjdGlvbiBhbmQgY2hvb3NlIHRoZSBjYW1lcmEgaW4gdmlkZW8gbW9kZVxuICAgKiBPbiBhcHBsZSBkZXZpY2VzIG11bHRpcGxlIG11c3QgYmUgc2V0IHRvIGZhbHNlLiAgQWNjZXB0ZWRGaWxlcyBtYXkgbmVlZCB0b1xuICAgKiBiZSBzZXQgdG8gYW4gYXBwcm9wcmlhdGUgbWltZSB0eXBlIChlLmcuIFwiaW1hZ2UvKlwiLCBcImF1ZGlvLypcIiwgb3IgXCJ2aWRlby8qXCIpLlxuICAgKi8gY2FwdHVyZTogbnVsbCxcbiAgICAvKipcbiAgICogKipEZXByZWNhdGVkKiouIFVzZSBgcmVuYW1lRmlsZWAgaW5zdGVhZC5cbiAgICovIHJlbmFtZUZpbGVuYW1lOiBudWxsLFxuICAgIC8qKlxuICAgKiBBIGZ1bmN0aW9uIHRoYXQgaXMgaW52b2tlZCBiZWZvcmUgdGhlIGZpbGUgaXMgdXBsb2FkZWQgdG8gdGhlIHNlcnZlciBhbmQgcmVuYW1lcyB0aGUgZmlsZS5cbiAgICogVGhpcyBmdW5jdGlvbiBnZXRzIHRoZSBgRmlsZWAgYXMgYXJndW1lbnQgYW5kIGNhbiB1c2UgdGhlIGBmaWxlLm5hbWVgLiBUaGUgYWN0dWFsIG5hbWUgb2YgdGhlXG4gICAqIGZpbGUgdGhhdCBnZXRzIHVzZWQgZHVyaW5nIHRoZSB1cGxvYWQgY2FuIGJlIGFjY2Vzc2VkIHRocm91Z2ggYGZpbGUudXBsb2FkLmZpbGVuYW1lYC5cbiAgICovIHJlbmFtZUZpbGU6IG51bGwsXG4gICAgLyoqXG4gICAqIElmIGB0cnVlYCB0aGUgZmFsbGJhY2sgd2lsbCBiZSBmb3JjZWQuIFRoaXMgaXMgdmVyeSB1c2VmdWwgdG8gdGVzdCB5b3VyIHNlcnZlclxuICAgKiBpbXBsZW1lbnRhdGlvbnMgZmlyc3QgYW5kIG1ha2Ugc3VyZSB0aGF0IGV2ZXJ5dGhpbmcgd29ya3MgYXNcbiAgICogZXhwZWN0ZWQgd2l0aG91dCBkcm9wem9uZSBpZiB5b3UgZXhwZXJpZW5jZSBwcm9ibGVtcywgYW5kIHRvIHRlc3RcbiAgICogaG93IHlvdXIgZmFsbGJhY2tzIHdpbGwgbG9vay5cbiAgICovIGZvcmNlRmFsbGJhY2s6IGZhbHNlLFxuICAgIC8qKlxuICAgKiBUaGUgdGV4dCB1c2VkIGJlZm9yZSBhbnkgZmlsZXMgYXJlIGRyb3BwZWQuXG4gICAqLyBkaWN0RGVmYXVsdE1lc3NhZ2U6IFwiRHJvcCBmaWxlcyBoZXJlIHRvIHVwbG9hZFwiLFxuICAgIC8qKlxuICAgKiBUaGUgdGV4dCB0aGF0IHJlcGxhY2VzIHRoZSBkZWZhdWx0IG1lc3NhZ2UgdGV4dCBpdCB0aGUgYnJvd3NlciBpcyBub3Qgc3VwcG9ydGVkLlxuICAgKi8gZGljdEZhbGxiYWNrTWVzc2FnZTogXCJZb3VyIGJyb3dzZXIgZG9lcyBub3Qgc3VwcG9ydCBkcmFnJ24nZHJvcCBmaWxlIHVwbG9hZHMuXCIsXG4gICAgLyoqXG4gICAqIFRoZSB0ZXh0IHRoYXQgd2lsbCBiZSBhZGRlZCBiZWZvcmUgdGhlIGZhbGxiYWNrIGZvcm0uXG4gICAqIElmIHlvdSBwcm92aWRlIGEgIGZhbGxiYWNrIGVsZW1lbnQgeW91cnNlbGYsIG9yIGlmIHRoaXMgb3B0aW9uIGlzIGBudWxsYCB0aGlzIHdpbGxcbiAgICogYmUgaWdub3JlZC5cbiAgICovIGRpY3RGYWxsYmFja1RleHQ6IFwiUGxlYXNlIHVzZSB0aGUgZmFsbGJhY2sgZm9ybSBiZWxvdyB0byB1cGxvYWQgeW91ciBmaWxlcyBsaWtlIGluIHRoZSBvbGRlbiBkYXlzLlwiLFxuICAgIC8qKlxuICAgKiBJZiB0aGUgZmlsZXNpemUgaXMgdG9vIGJpZy5cbiAgICogYHt7ZmlsZXNpemV9fWAgYW5kIGB7e21heEZpbGVzaXplfX1gIHdpbGwgYmUgcmVwbGFjZWQgd2l0aCB0aGUgcmVzcGVjdGl2ZSBjb25maWd1cmF0aW9uIHZhbHVlcy5cbiAgICovIGRpY3RGaWxlVG9vQmlnOiBcIkZpbGUgaXMgdG9vIGJpZyAoe3tmaWxlc2l6ZX19TWlCKS4gTWF4IGZpbGVzaXplOiB7e21heEZpbGVzaXplfX1NaUIuXCIsXG4gICAgLyoqXG4gICAqIElmIHRoZSBmaWxlIGRvZXNuJ3QgbWF0Y2ggdGhlIGZpbGUgdHlwZS5cbiAgICovIGRpY3RJbnZhbGlkRmlsZVR5cGU6IFwiWW91IGNhbid0IHVwbG9hZCBmaWxlcyBvZiB0aGlzIHR5cGUuXCIsXG4gICAgLyoqXG4gICAqIElmIHRoZSBzZXJ2ZXIgcmVzcG9uc2Ugd2FzIGludmFsaWQuXG4gICAqIGB7e3N0YXR1c0NvZGV9fWAgd2lsbCBiZSByZXBsYWNlZCB3aXRoIHRoZSBzZXJ2ZXJzIHN0YXR1cyBjb2RlLlxuICAgKi8gZGljdFJlc3BvbnNlRXJyb3I6IFwiU2VydmVyIHJlc3BvbmRlZCB3aXRoIHt7c3RhdHVzQ29kZX19IGNvZGUuXCIsXG4gICAgLyoqXG4gICAqIElmIGBhZGRSZW1vdmVMaW5rc2AgaXMgdHJ1ZSwgdGhlIHRleHQgdG8gYmUgdXNlZCBmb3IgdGhlIGNhbmNlbCB1cGxvYWQgbGluay5cbiAgICovIGRpY3RDYW5jZWxVcGxvYWQ6IFwiQ2FuY2VsIHVwbG9hZFwiLFxuICAgIC8qKlxuICAgKiBUaGUgdGV4dCB0aGF0IGlzIGRpc3BsYXllZCBpZiBhbiB1cGxvYWQgd2FzIG1hbnVhbGx5IGNhbmNlbGVkXG4gICAqLyBkaWN0VXBsb2FkQ2FuY2VsZWQ6IFwiVXBsb2FkIGNhbmNlbGVkLlwiLFxuICAgIC8qKlxuICAgKiBJZiBgYWRkUmVtb3ZlTGlua3NgIGlzIHRydWUsIHRoZSB0ZXh0IHRvIGJlIHVzZWQgZm9yIGNvbmZpcm1hdGlvbiB3aGVuIGNhbmNlbGxpbmcgdXBsb2FkLlxuICAgKi8gZGljdENhbmNlbFVwbG9hZENvbmZpcm1hdGlvbjogXCJBcmUgeW91IHN1cmUgeW91IHdhbnQgdG8gY2FuY2VsIHRoaXMgdXBsb2FkP1wiLFxuICAgIC8qKlxuICAgKiBJZiBgYWRkUmVtb3ZlTGlua3NgIGlzIHRydWUsIHRoZSB0ZXh0IHRvIGJlIHVzZWQgdG8gcmVtb3ZlIGEgZmlsZS5cbiAgICovIGRpY3RSZW1vdmVGaWxlOiBcIlJlbW92ZSBmaWxlXCIsXG4gICAgLyoqXG4gICAqIElmIHRoaXMgaXMgbm90IG51bGwsIHRoZW4gdGhlIHVzZXIgd2lsbCBiZSBwcm9tcHRlZCBiZWZvcmUgcmVtb3ZpbmcgYSBmaWxlLlxuICAgKi8gZGljdFJlbW92ZUZpbGVDb25maXJtYXRpb246IG51bGwsXG4gICAgLyoqXG4gICAqIERpc3BsYXllZCBpZiBgbWF4RmlsZXNgIGlzIHN0IGFuZCBleGNlZWRlZC5cbiAgICogVGhlIHN0cmluZyBge3ttYXhGaWxlc319YCB3aWxsIGJlIHJlcGxhY2VkIGJ5IHRoZSBjb25maWd1cmF0aW9uIHZhbHVlLlxuICAgKi8gZGljdE1heEZpbGVzRXhjZWVkZWQ6IFwiWW91IGNhbiBub3QgdXBsb2FkIGFueSBtb3JlIGZpbGVzLlwiLFxuICAgIC8qKlxuICAgKiBBbGxvd3MgeW91IHRvIHRyYW5zbGF0ZSB0aGUgZGlmZmVyZW50IHVuaXRzLiBTdGFydGluZyB3aXRoIGB0YmAgZm9yIHRlcmFieXRlcyBhbmQgZ29pbmcgZG93biB0b1xuICAgKiBgYmAgZm9yIGJ5dGVzLlxuICAgKi8gZGljdEZpbGVTaXplVW5pdHM6IHtcbiAgICAgICAgdGI6IFwiVEJcIixcbiAgICAgICAgZ2I6IFwiR0JcIixcbiAgICAgICAgbWI6IFwiTUJcIixcbiAgICAgICAga2I6IFwiS0JcIixcbiAgICAgICAgYjogXCJiXCJcbiAgICB9LFxuICAgIC8qKlxuICAgKiBDYWxsZWQgd2hlbiBkcm9wem9uZSBpbml0aWFsaXplZFxuICAgKiBZb3UgY2FuIGFkZCBldmVudCBsaXN0ZW5lcnMgaGVyZVxuICAgKi8gaW5pdCAoKSB7XG4gICAgfSxcbiAgICAvKipcbiAgICogQ2FuIGJlIGFuICoqb2JqZWN0Kiogb2YgYWRkaXRpb25hbCBwYXJhbWV0ZXJzIHRvIHRyYW5zZmVyIHRvIHRoZSBzZXJ2ZXIsICoqb3IqKiBhIGBGdW5jdGlvbmBcbiAgICogdGhhdCBnZXRzIGludm9rZWQgd2l0aCB0aGUgYGZpbGVzYCwgYHhocmAgYW5kLCBpZiBpdCdzIGEgY2h1bmtlZCB1cGxvYWQsIGBjaHVua2AgYXJndW1lbnRzLiBJbiBjYXNlXG4gICAqIG9mIGEgZnVuY3Rpb24sIHRoaXMgbmVlZHMgdG8gcmV0dXJuIGEgbWFwLlxuICAgKlxuICAgKiBUaGUgZGVmYXVsdCBpbXBsZW1lbnRhdGlvbiBkb2VzIG5vdGhpbmcgZm9yIG5vcm1hbCB1cGxvYWRzLCBidXQgYWRkcyByZWxldmFudCBpbmZvcm1hdGlvbiBmb3JcbiAgICogY2h1bmtlZCB1cGxvYWRzLlxuICAgKlxuICAgKiBUaGlzIGlzIHRoZSBzYW1lIGFzIGFkZGluZyBoaWRkZW4gaW5wdXQgZmllbGRzIGluIHRoZSBmb3JtIGVsZW1lbnQuXG4gICAqLyBwYXJhbXMgKGZpbGVzLCB4aHIsIGNodW5rKSB7XG4gICAgICAgIGlmIChjaHVuaykgcmV0dXJuIHtcbiAgICAgICAgICAgIGR6dXVpZDogY2h1bmsuZmlsZS51cGxvYWQudXVpZCxcbiAgICAgICAgICAgIGR6Y2h1bmtpbmRleDogY2h1bmsuaW5kZXgsXG4gICAgICAgICAgICBkenRvdGFsZmlsZXNpemU6IGNodW5rLmZpbGUuc2l6ZSxcbiAgICAgICAgICAgIGR6Y2h1bmtzaXplOiB0aGlzLm9wdGlvbnMuY2h1bmtTaXplLFxuICAgICAgICAgICAgZHp0b3RhbGNodW5rY291bnQ6IGNodW5rLmZpbGUudXBsb2FkLnRvdGFsQ2h1bmtDb3VudCxcbiAgICAgICAgICAgIGR6Y2h1bmtieXRlb2Zmc2V0OiBjaHVuay5pbmRleCAqIHRoaXMub3B0aW9ucy5jaHVua1NpemVcbiAgICAgICAgfTtcbiAgICB9LFxuICAgIC8qKlxuICAgKiBBIGZ1bmN0aW9uIHRoYXQgZ2V0cyBhIFtmaWxlXShodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL0RPTS9GaWxlKVxuICAgKiBhbmQgYSBgZG9uZWAgZnVuY3Rpb24gYXMgcGFyYW1ldGVycy5cbiAgICpcbiAgICogSWYgdGhlIGRvbmUgZnVuY3Rpb24gaXMgaW52b2tlZCB3aXRob3V0IGFyZ3VtZW50cywgdGhlIGZpbGUgaXMgXCJhY2NlcHRlZFwiIGFuZCB3aWxsXG4gICAqIGJlIHByb2Nlc3NlZC4gSWYgeW91IHBhc3MgYW4gZXJyb3IgbWVzc2FnZSwgdGhlIGZpbGUgaXMgcmVqZWN0ZWQsIGFuZCB0aGUgZXJyb3JcbiAgICogbWVzc2FnZSB3aWxsIGJlIGRpc3BsYXllZC5cbiAgICogVGhpcyBmdW5jdGlvbiB3aWxsIG5vdCBiZSBjYWxsZWQgaWYgdGhlIGZpbGUgaXMgdG9vIGJpZyBvciBkb2Vzbid0IG1hdGNoIHRoZSBtaW1lIHR5cGVzLlxuICAgKi8gYWNjZXB0IChmaWxlLCBkb25lKSB7XG4gICAgICAgIHJldHVybiBkb25lKCk7XG4gICAgfSxcbiAgICAvKipcbiAgICogVGhlIGNhbGxiYWNrIHRoYXQgd2lsbCBiZSBpbnZva2VkIHdoZW4gYWxsIGNodW5rcyBoYXZlIGJlZW4gdXBsb2FkZWQgZm9yIGEgZmlsZS5cbiAgICogSXQgZ2V0cyB0aGUgZmlsZSBmb3Igd2hpY2ggdGhlIGNodW5rcyBoYXZlIGJlZW4gdXBsb2FkZWQgYXMgdGhlIGZpcnN0IHBhcmFtZXRlcixcbiAgICogYW5kIHRoZSBgZG9uZWAgZnVuY3Rpb24gYXMgc2Vjb25kLiBgZG9uZSgpYCBuZWVkcyB0byBiZSBpbnZva2VkIHdoZW4gZXZlcnl0aGluZ1xuICAgKiBuZWVkZWQgdG8gZmluaXNoIHRoZSB1cGxvYWQgcHJvY2VzcyBpcyBkb25lLlxuICAgKi8gY2h1bmtzVXBsb2FkZWQ6IGZ1bmN0aW9uKGZpbGUsIGRvbmUpIHtcbiAgICAgICAgZG9uZSgpO1xuICAgIH0sXG4gICAgLyoqXG4gICAqIFNlbmRzIHRoZSBmaWxlIGFzIGJpbmFyeSBibG9iIGluIGJvZHkgaW5zdGVhZCBvZiBmb3JtIGRhdGEuXG4gICAqIElmIHRoaXMgaXMgc2V0LCB0aGUgYHBhcmFtc2Agb3B0aW9uIHdpbGwgYmUgaWdub3JlZC5cbiAgICogSXQncyBhbiBlcnJvciB0byBzZXQgdGhpcyB0byBgdHJ1ZWAgYWxvbmcgd2l0aCBgdXBsb2FkTXVsdGlwbGVgIHNpbmNlXG4gICAqIG11bHRpcGxlIGZpbGVzIGNhbm5vdCBiZSBpbiBhIHNpbmdsZSBiaW5hcnkgYm9keS5cbiAgICovIGJpbmFyeUJvZHk6IGZhbHNlLFxuICAgIC8qKlxuICAgKiBHZXRzIGNhbGxlZCB3aGVuIHRoZSBicm93c2VyIGlzIG5vdCBzdXBwb3J0ZWQuXG4gICAqIFRoZSBkZWZhdWx0IGltcGxlbWVudGF0aW9uIHNob3dzIHRoZSBmYWxsYmFjayBpbnB1dCBmaWVsZCBhbmQgYWRkc1xuICAgKiBhIHRleHQuXG4gICAqLyBmYWxsYmFjayAoKSB7XG4gICAgICAgIC8vIFRoaXMgY29kZSBzaG91bGQgcGFzcyBpbiBJRTcuLi4gOihcbiAgICAgICAgbGV0IG1lc3NhZ2VFbGVtZW50O1xuICAgICAgICB0aGlzLmVsZW1lbnQuY2xhc3NOYW1lID0gYCR7dGhpcy5lbGVtZW50LmNsYXNzTmFtZX0gZHotYnJvd3Nlci1ub3Qtc3VwcG9ydGVkYDtcbiAgICAgICAgZm9yIChsZXQgY2hpbGQgb2YgdGhpcy5lbGVtZW50LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpKWlmICgvKF58IClkei1tZXNzYWdlKCR8ICkvLnRlc3QoY2hpbGQuY2xhc3NOYW1lKSkge1xuICAgICAgICAgICAgbWVzc2FnZUVsZW1lbnQgPSBjaGlsZDtcbiAgICAgICAgICAgIGNoaWxkLmNsYXNzTmFtZSA9IFwiZHotbWVzc2FnZVwiOyAvLyBSZW1vdmVzIHRoZSAnZHotZGVmYXVsdCcgY2xhc3NcbiAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICB9XG4gICAgICAgIGlmICghbWVzc2FnZUVsZW1lbnQpIHtcbiAgICAgICAgICAgIG1lc3NhZ2VFbGVtZW50ID0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5jcmVhdGVFbGVtZW50KCc8ZGl2IGNsYXNzPVwiZHotbWVzc2FnZVwiPjxzcGFuPjwvc3Bhbj48L2Rpdj4nKTtcbiAgICAgICAgICAgIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZChtZXNzYWdlRWxlbWVudCk7XG4gICAgICAgIH1cbiAgICAgICAgbGV0IHNwYW4gPSBtZXNzYWdlRWxlbWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZShcInNwYW5cIilbMF07XG4gICAgICAgIGlmIChzcGFuKSB7XG4gICAgICAgICAgICBpZiAoc3Bhbi50ZXh0Q29udGVudCAhPSBudWxsKSBzcGFuLnRleHRDb250ZW50ID0gdGhpcy5vcHRpb25zLmRpY3RGYWxsYmFja01lc3NhZ2U7XG4gICAgICAgICAgICBlbHNlIGlmIChzcGFuLmlubmVyVGV4dCAhPSBudWxsKSBzcGFuLmlubmVyVGV4dCA9IHRoaXMub3B0aW9ucy5kaWN0RmFsbGJhY2tNZXNzYWdlO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuYXBwZW5kQ2hpbGQodGhpcy5nZXRGYWxsYmFja0Zvcm0oKSk7XG4gICAgfSxcbiAgICAvKipcbiAgICogR2V0cyBjYWxsZWQgdG8gY2FsY3VsYXRlIHRoZSB0aHVtYm5haWwgZGltZW5zaW9ucy5cbiAgICpcbiAgICogSXQgZ2V0cyBgZmlsZWAsIGB3aWR0aGAgYW5kIGBoZWlnaHRgIChib3RoIG1heSBiZSBgbnVsbGApIGFzIHBhcmFtZXRlcnMgYW5kIG11c3QgcmV0dXJuIGFuIG9iamVjdCBjb250YWluaW5nOlxuICAgKlxuICAgKiAgLSBgc3JjV2lkdGhgICYgYHNyY0hlaWdodGAgKHJlcXVpcmVkKVxuICAgKiAgLSBgdHJnV2lkdGhgICYgYHRyZ0hlaWdodGAgKHJlcXVpcmVkKVxuICAgKiAgLSBgc3JjWGAgJiBgc3JjWWAgKG9wdGlvbmFsLCBkZWZhdWx0IGAwYClcbiAgICogIC0gYHRyZ1hgICYgYHRyZ1lgIChvcHRpb25hbCwgZGVmYXVsdCBgMGApXG4gICAqXG4gICAqIFRob3NlIHZhbHVlcyBhcmUgZ29pbmcgdG8gYmUgdXNlZCBieSBgY3R4LmRyYXdJbWFnZSgpYC5cbiAgICovIHJlc2l6ZSAoZmlsZSwgd2lkdGgsIGhlaWdodCwgcmVzaXplTWV0aG9kKSB7XG4gICAgICAgIGxldCBpbmZvID0ge1xuICAgICAgICAgICAgc3JjWDogMCxcbiAgICAgICAgICAgIHNyY1k6IDAsXG4gICAgICAgICAgICBzcmNXaWR0aDogZmlsZS53aWR0aCxcbiAgICAgICAgICAgIHNyY0hlaWdodDogZmlsZS5oZWlnaHRcbiAgICAgICAgfTtcbiAgICAgICAgbGV0IHNyY1JhdGlvID0gZmlsZS53aWR0aCAvIGZpbGUuaGVpZ2h0O1xuICAgICAgICAvLyBBdXRvbWF0aWNhbGx5IGNhbGN1bGF0ZSBkaW1lbnNpb25zIGlmIG5vdCBzcGVjaWZpZWRcbiAgICAgICAgaWYgKHdpZHRoID09IG51bGwgJiYgaGVpZ2h0ID09IG51bGwpIHtcbiAgICAgICAgICAgIHdpZHRoID0gaW5mby5zcmNXaWR0aDtcbiAgICAgICAgICAgIGhlaWdodCA9IGluZm8uc3JjSGVpZ2h0O1xuICAgICAgICB9IGVsc2UgaWYgKHdpZHRoID09IG51bGwpIHdpZHRoID0gaGVpZ2h0ICogc3JjUmF0aW87XG4gICAgICAgIGVsc2UgaWYgKGhlaWdodCA9PSBudWxsKSBoZWlnaHQgPSB3aWR0aCAvIHNyY1JhdGlvO1xuICAgICAgICAvLyBNYWtlIHN1cmUgaW1hZ2VzIGFyZW4ndCB1cHNjYWxlZFxuICAgICAgICB3aWR0aCA9IE1hdGgubWluKHdpZHRoLCBpbmZvLnNyY1dpZHRoKTtcbiAgICAgICAgaGVpZ2h0ID0gTWF0aC5taW4oaGVpZ2h0LCBpbmZvLnNyY0hlaWdodCk7XG4gICAgICAgIGxldCB0cmdSYXRpbyA9IHdpZHRoIC8gaGVpZ2h0O1xuICAgICAgICBpZiAoaW5mby5zcmNXaWR0aCA+IHdpZHRoIHx8IGluZm8uc3JjSGVpZ2h0ID4gaGVpZ2h0KSB7XG4gICAgICAgICAgICAvLyBJbWFnZSBpcyBiaWdnZXIgYW5kIG5lZWRzIHJlc2NhbGluZ1xuICAgICAgICAgICAgaWYgKHJlc2l6ZU1ldGhvZCA9PT0gXCJjcm9wXCIpIHtcbiAgICAgICAgICAgICAgICBpZiAoc3JjUmF0aW8gPiB0cmdSYXRpbykge1xuICAgICAgICAgICAgICAgICAgICBpbmZvLnNyY0hlaWdodCA9IGZpbGUuaGVpZ2h0O1xuICAgICAgICAgICAgICAgICAgICBpbmZvLnNyY1dpZHRoID0gaW5mby5zcmNIZWlnaHQgKiB0cmdSYXRpbztcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBpbmZvLnNyY1dpZHRoID0gZmlsZS53aWR0aDtcbiAgICAgICAgICAgICAgICAgICAgaW5mby5zcmNIZWlnaHQgPSBpbmZvLnNyY1dpZHRoIC8gdHJnUmF0aW87XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSBlbHNlIGlmIChyZXNpemVNZXRob2QgPT09IFwiY29udGFpblwiKSB7XG4gICAgICAgICAgICAgICAgLy8gTWV0aG9kICdjb250YWluJ1xuICAgICAgICAgICAgICAgIGlmIChzcmNSYXRpbyA+IHRyZ1JhdGlvKSBoZWlnaHQgPSB3aWR0aCAvIHNyY1JhdGlvO1xuICAgICAgICAgICAgICAgIGVsc2Ugd2lkdGggPSBoZWlnaHQgKiBzcmNSYXRpbztcbiAgICAgICAgICAgIH0gZWxzZSB0aHJvdyBuZXcgRXJyb3IoYFVua25vd24gcmVzaXplTWV0aG9kICcke3Jlc2l6ZU1ldGhvZH0nYCk7XG4gICAgICAgIH1cbiAgICAgICAgaW5mby5zcmNYID0gKGZpbGUud2lkdGggLSBpbmZvLnNyY1dpZHRoKSAvIDI7XG4gICAgICAgIGluZm8uc3JjWSA9IChmaWxlLmhlaWdodCAtIGluZm8uc3JjSGVpZ2h0KSAvIDI7XG4gICAgICAgIGluZm8udHJnV2lkdGggPSB3aWR0aDtcbiAgICAgICAgaW5mby50cmdIZWlnaHQgPSBoZWlnaHQ7XG4gICAgICAgIHJldHVybiBpbmZvO1xuICAgIH0sXG4gICAgLyoqXG4gICAqIENhbiBiZSB1c2VkIHRvIHRyYW5zZm9ybSB0aGUgZmlsZSAoZm9yIGV4YW1wbGUsIHJlc2l6ZSBhbiBpbWFnZSBpZiBuZWNlc3NhcnkpLlxuICAgKlxuICAgKiBUaGUgZGVmYXVsdCBpbXBsZW1lbnRhdGlvbiB1c2VzIGByZXNpemVXaWR0aGAgYW5kIGByZXNpemVIZWlnaHRgIChpZiBwcm92aWRlZCkgYW5kIHJlc2l6ZXNcbiAgICogaW1hZ2VzIGFjY29yZGluZyB0byB0aG9zZSBkaW1lbnNpb25zLlxuICAgKlxuICAgKiBHZXRzIHRoZSBgZmlsZWAgYXMgdGhlIGZpcnN0IHBhcmFtZXRlciwgYW5kIGEgYGRvbmUoKWAgZnVuY3Rpb24gYXMgdGhlIHNlY29uZCwgdGhhdCBuZWVkc1xuICAgKiB0byBiZSBpbnZva2VkIHdpdGggdGhlIGZpbGUgd2hlbiB0aGUgdHJhbnNmb3JtYXRpb24gaXMgZG9uZS5cbiAgICovIHRyYW5zZm9ybUZpbGUgKGZpbGUsIGRvbmUpIHtcbiAgICAgICAgaWYgKCh0aGlzLm9wdGlvbnMucmVzaXplV2lkdGggfHwgdGhpcy5vcHRpb25zLnJlc2l6ZUhlaWdodCkgJiYgZmlsZS50eXBlLm1hdGNoKC9pbWFnZS4qLykpIHJldHVybiB0aGlzLnJlc2l6ZUltYWdlKGZpbGUsIHRoaXMub3B0aW9ucy5yZXNpemVXaWR0aCwgdGhpcy5vcHRpb25zLnJlc2l6ZUhlaWdodCwgdGhpcy5vcHRpb25zLnJlc2l6ZU1ldGhvZCwgZG9uZSk7XG4gICAgICAgIGVsc2UgcmV0dXJuIGRvbmUoZmlsZSk7XG4gICAgfSxcbiAgICAvKipcbiAgICogQSBzdHJpbmcgdGhhdCBjb250YWlucyB0aGUgdGVtcGxhdGUgdXNlZCBmb3IgZWFjaCBkcm9wcGVkXG4gICAqIGZpbGUuIENoYW5nZSBpdCB0byBmdWxmaWxsIHlvdXIgbmVlZHMgYnV0IG1ha2Ugc3VyZSB0byBwcm9wZXJseVxuICAgKiBwcm92aWRlIGFsbCBlbGVtZW50cy5cbiAgICpcbiAgICogSWYgeW91IHdhbnQgdG8gdXNlIGFuIGFjdHVhbCBIVE1MIGVsZW1lbnQgaW5zdGVhZCBvZiBwcm92aWRpbmcgYSBTdHJpbmdcbiAgICogYXMgYSBjb25maWcgb3B0aW9uLCB5b3UgY291bGQgY3JlYXRlIGEgZGl2IHdpdGggdGhlIGlkIGB0cGxgLFxuICAgKiBwdXQgdGhlIHRlbXBsYXRlIGluc2lkZSBpdCBhbmQgcHJvdmlkZSB0aGUgZWxlbWVudCBsaWtlIHRoaXM6XG4gICAqXG4gICAqICAgICBkb2N1bWVudFxuICAgKiAgICAgICAucXVlcnlTZWxlY3RvcignI3RwbCcpXG4gICAqICAgICAgIC5pbm5lckhUTUxcbiAgICpcbiAgICovIHByZXZpZXdUZW1wbGF0ZTogKC8qQF9fUFVSRV9fKi8kcGFyY2VsJGludGVyb3BEZWZhdWx0KCRmZDYwMzFmODhkY2UyZTMyJGV4cG9ydHMpKSxcbiAgICAvKlxuICAgVGhvc2UgZnVuY3Rpb25zIHJlZ2lzdGVyIHRoZW1zZWx2ZXMgdG8gdGhlIGV2ZW50cyBvbiBpbml0IGFuZCBoYW5kbGUgYWxsXG4gICB0aGUgdXNlciBpbnRlcmZhY2Ugc3BlY2lmaWMgc3R1ZmYuIE92ZXJ3cml0aW5nIHRoZW0gd29uJ3QgYnJlYWsgdGhlIHVwbG9hZFxuICAgYnV0IGNhbiBicmVhayB0aGUgd2F5IGl0J3MgZGlzcGxheWVkLlxuICAgWW91IGNhbiBvdmVyd3JpdGUgdGhlbSBpZiB5b3UgZG9uJ3QgbGlrZSB0aGUgZGVmYXVsdCBiZWhhdmlvci4gSWYgeW91IGp1c3RcbiAgIHdhbnQgdG8gYWRkIGFuIGFkZGl0aW9uYWwgZXZlbnQgaGFuZGxlciwgcmVnaXN0ZXIgaXQgb24gdGhlIGRyb3B6b25lIG9iamVjdFxuICAgYW5kIGRvbid0IG92ZXJ3cml0ZSB0aG9zZSBvcHRpb25zLlxuICAgKi8gLy8gVGhvc2UgYXJlIHNlbGYgZXhwbGFuYXRvcnkgYW5kIHNpbXBseSBjb25jZXJuIHRoZSBEcmFnbkRyb3AuXG4gICAgZHJvcCAoZSkge1xuICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJkei1kcmFnLWhvdmVyXCIpO1xuICAgIH0sXG4gICAgZHJhZ3N0YXJ0IChlKSB7XG4gICAgfSxcbiAgICBkcmFnZW5kIChlKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcImR6LWRyYWctaG92ZXJcIik7XG4gICAgfSxcbiAgICBkcmFnZW50ZXIgKGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiZHotZHJhZy1ob3ZlclwiKTtcbiAgICB9LFxuICAgIGRyYWdvdmVyIChlKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LWRyYWctaG92ZXJcIik7XG4gICAgfSxcbiAgICBkcmFnbGVhdmUgKGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwiZHotZHJhZy1ob3ZlclwiKTtcbiAgICB9LFxuICAgIHBhc3RlIChlKSB7XG4gICAgfSxcbiAgICAvLyBDYWxsZWQgd2hlbmV2ZXIgdGhlcmUgYXJlIG5vIGZpbGVzIGxlZnQgaW4gdGhlIGRyb3B6b25lIGFueW1vcmUsIGFuZCB0aGVcbiAgICAvLyBkcm9wem9uZSBzaG91bGQgYmUgZGlzcGxheWVkIGFzIGlmIGluIHRoZSBpbml0aWFsIHN0YXRlLlxuICAgIHJlc2V0ICgpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKFwiZHotc3RhcnRlZFwiKTtcbiAgICB9LFxuICAgIC8vIENhbGxlZCB3aGVuIGEgZmlsZSBpcyBhZGRlZCB0byB0aGUgcXVldWVcbiAgICAvLyBSZWNlaXZlcyBgZmlsZWBcbiAgICBhZGRlZGZpbGUgKGZpbGUpIHtcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudCA9PT0gdGhpcy5wcmV2aWV3c0NvbnRhaW5lcikgdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1zdGFydGVkXCIpO1xuICAgICAgICBpZiAodGhpcy5wcmV2aWV3c0NvbnRhaW5lciAmJiAhdGhpcy5vcHRpb25zLmRpc2FibGVQcmV2aWV3cykge1xuICAgICAgICAgICAgZmlsZS5wcmV2aWV3RWxlbWVudCA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuY3JlYXRlRWxlbWVudCh0aGlzLm9wdGlvbnMucHJldmlld1RlbXBsYXRlLnRyaW0oKSk7XG4gICAgICAgICAgICBmaWxlLnByZXZpZXdUZW1wbGF0ZSA9IGZpbGUucHJldmlld0VsZW1lbnQ7IC8vIEJhY2t3YXJkcyBjb21wYXRpYmlsaXR5XG4gICAgICAgICAgICB0aGlzLnByZXZpZXdzQ29udGFpbmVyLmFwcGVuZENoaWxkKGZpbGUucHJldmlld0VsZW1lbnQpO1xuICAgICAgICAgICAgZm9yICh2YXIgbm9kZSBvZiBmaWxlLnByZXZpZXdFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCJbZGF0YS1kei1uYW1lXVwiKSlub2RlLnRleHRDb250ZW50ID0gZmlsZS5uYW1lO1xuICAgICAgICAgICAgZm9yIChub2RlIG9mIGZpbGUucHJldmlld0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIltkYXRhLWR6LXNpemVdXCIpKW5vZGUuaW5uZXJIVE1MID0gdGhpcy5maWxlc2l6ZShmaWxlLnNpemUpO1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5hZGRSZW1vdmVMaW5rcykge1xuICAgICAgICAgICAgICAgIGZpbGUuX3JlbW92ZUxpbmsgPSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmNyZWF0ZUVsZW1lbnQoYDxhIGNsYXNzPVwiZHotcmVtb3ZlXCIgaHJlZj1cImphdmFzY3JpcHQ6dW5kZWZpbmVkO1wiIGRhdGEtZHotcmVtb3ZlPiR7dGhpcy5vcHRpb25zLmRpY3RSZW1vdmVGaWxlfTwvYT5gKTtcbiAgICAgICAgICAgICAgICBmaWxlLnByZXZpZXdFbGVtZW50LmFwcGVuZENoaWxkKGZpbGUuX3JlbW92ZUxpbmspO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgbGV0IHJlbW92ZUZpbGVFdmVudCA9IChlKT0+e1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICAgICAgICAgIGlmIChmaWxlLnN0YXR1cyA9PT0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5VUExPQURJTkcpIHJldHVybiAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmNvbmZpcm0odGhpcy5vcHRpb25zLmRpY3RDYW5jZWxVcGxvYWRDb25maXJtYXRpb24sICgpPT50aGlzLnJlbW92ZUZpbGUoZmlsZSlcbiAgICAgICAgICAgICAgICApO1xuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLmRpY3RSZW1vdmVGaWxlQ29uZmlybWF0aW9uKSByZXR1cm4gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5jb25maXJtKHRoaXMub3B0aW9ucy5kaWN0UmVtb3ZlRmlsZUNvbmZpcm1hdGlvbiwgKCk9PnRoaXMucmVtb3ZlRmlsZShmaWxlKVxuICAgICAgICAgICAgICAgICAgICApO1xuICAgICAgICAgICAgICAgICAgICBlbHNlIHJldHVybiB0aGlzLnJlbW92ZUZpbGUoZmlsZSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIGZvciAobGV0IHJlbW92ZUxpbmsgb2YgZmlsZS5wcmV2aWV3RWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiW2RhdGEtZHotcmVtb3ZlXVwiKSlyZW1vdmVMaW5rLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCByZW1vdmVGaWxlRXZlbnQpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICAvLyBDYWxsZWQgd2hlbmV2ZXIgYSBmaWxlIGlzIHJlbW92ZWQuXG4gICAgcmVtb3ZlZGZpbGUgKGZpbGUpIHtcbiAgICAgICAgaWYgKGZpbGUucHJldmlld0VsZW1lbnQgIT0gbnVsbCAmJiBmaWxlLnByZXZpZXdFbGVtZW50LnBhcmVudE5vZGUgIT0gbnVsbCkgZmlsZS5wcmV2aWV3RWxlbWVudC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGZpbGUucHJldmlld0VsZW1lbnQpO1xuICAgICAgICByZXR1cm4gdGhpcy5fdXBkYXRlTWF4RmlsZXNSZWFjaGVkQ2xhc3MoKTtcbiAgICB9LFxuICAgIC8vIENhbGxlZCB3aGVuIGEgdGh1bWJuYWlsIGhhcyBiZWVuIGdlbmVyYXRlZFxuICAgIC8vIFJlY2VpdmVzIGBmaWxlYCBhbmQgYGRhdGFVcmxgXG4gICAgdGh1bWJuYWlsIChmaWxlLCBkYXRhVXJsKSB7XG4gICAgICAgIGlmIChmaWxlLnByZXZpZXdFbGVtZW50KSB7XG4gICAgICAgICAgICBmaWxlLnByZXZpZXdFbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoXCJkei1maWxlLXByZXZpZXdcIik7XG4gICAgICAgICAgICBmb3IgKGxldCB0aHVtYm5haWxFbGVtZW50IG9mIGZpbGUucHJldmlld0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIltkYXRhLWR6LXRodW1ibmFpbF1cIikpe1xuICAgICAgICAgICAgICAgIHRodW1ibmFpbEVsZW1lbnQuYWx0ID0gZmlsZS5uYW1lO1xuICAgICAgICAgICAgICAgIHRodW1ibmFpbEVsZW1lbnQuc3JjID0gZGF0YVVybDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBzZXRUaW1lb3V0KCgpPT5maWxlLnByZXZpZXdFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1pbWFnZS1wcmV2aWV3XCIpXG4gICAgICAgICAgICAsIDEpO1xuICAgICAgICB9XG4gICAgfSxcbiAgICAvLyBDYWxsZWQgd2hlbmV2ZXIgYW4gZXJyb3Igb2NjdXJzXG4gICAgLy8gUmVjZWl2ZXMgYGZpbGVgIGFuZCBgbWVzc2FnZWBcbiAgICBlcnJvciAoZmlsZSwgbWVzc2FnZSkge1xuICAgICAgICBpZiAoZmlsZS5wcmV2aWV3RWxlbWVudCkge1xuICAgICAgICAgICAgZmlsZS5wcmV2aWV3RWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiZHotZXJyb3JcIik7XG4gICAgICAgICAgICBpZiAodHlwZW9mIG1lc3NhZ2UgIT09IFwic3RyaW5nXCIgJiYgbWVzc2FnZS5lcnJvcikgbWVzc2FnZSA9IG1lc3NhZ2UuZXJyb3I7XG4gICAgICAgICAgICBmb3IgKGxldCBub2RlIG9mIGZpbGUucHJldmlld0VsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcIltkYXRhLWR6LWVycm9ybWVzc2FnZV1cIikpbm9kZS50ZXh0Q29udGVudCA9IG1lc3NhZ2U7XG4gICAgICAgIH1cbiAgICB9LFxuICAgIGVycm9ybXVsdGlwbGUgKCkge1xuICAgIH0sXG4gICAgLy8gQ2FsbGVkIHdoZW4gYSBmaWxlIGdldHMgcHJvY2Vzc2VkLiBTaW5jZSB0aGVyZSBpcyBhIGN1ZSwgbm90IGFsbCBhZGRlZFxuICAgIC8vIGZpbGVzIGFyZSBwcm9jZXNzZWQgaW1tZWRpYXRlbHkuXG4gICAgLy8gUmVjZWl2ZXMgYGZpbGVgXG4gICAgcHJvY2Vzc2luZyAoZmlsZSkge1xuICAgICAgICBpZiAoZmlsZS5wcmV2aWV3RWxlbWVudCkge1xuICAgICAgICAgICAgZmlsZS5wcmV2aWV3RWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiZHotcHJvY2Vzc2luZ1wiKTtcbiAgICAgICAgICAgIGlmIChmaWxlLl9yZW1vdmVMaW5rKSByZXR1cm4gZmlsZS5fcmVtb3ZlTGluay5pbm5lckhUTUwgPSB0aGlzLm9wdGlvbnMuZGljdENhbmNlbFVwbG9hZDtcbiAgICAgICAgfVxuICAgIH0sXG4gICAgcHJvY2Vzc2luZ211bHRpcGxlICgpIHtcbiAgICB9LFxuICAgIC8vIENhbGxlZCB3aGVuZXZlciB0aGUgdXBsb2FkIHByb2dyZXNzIGdldHMgdXBkYXRlZC5cbiAgICAvLyBSZWNlaXZlcyBgZmlsZWAsIGBwcm9ncmVzc2AgKHBlcmNlbnRhZ2UgMC0xMDApIGFuZCBgYnl0ZXNTZW50YC5cbiAgICAvLyBUbyBnZXQgdGhlIHRvdGFsIG51bWJlciBvZiBieXRlcyBvZiB0aGUgZmlsZSwgdXNlIGBmaWxlLnNpemVgXG4gICAgdXBsb2FkcHJvZ3Jlc3MgKGZpbGUsIHByb2dyZXNzLCBieXRlc1NlbnQpIHtcbiAgICAgICAgaWYgKGZpbGUucHJldmlld0VsZW1lbnQpIGZvciAobGV0IG5vZGUgb2YgZmlsZS5wcmV2aWV3RWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiW2RhdGEtZHotdXBsb2FkcHJvZ3Jlc3NdXCIpKW5vZGUubm9kZU5hbWUgPT09IFwiUFJPR1JFU1NcIiA/IG5vZGUudmFsdWUgPSBwcm9ncmVzcyA6IG5vZGUuc3R5bGUud2lkdGggPSBgJHtwcm9ncmVzc30lYDtcbiAgICB9LFxuICAgIC8vIENhbGxlZCB3aGVuZXZlciB0aGUgdG90YWwgdXBsb2FkIHByb2dyZXNzIGdldHMgdXBkYXRlZC5cbiAgICAvLyBDYWxsZWQgd2l0aCB0b3RhbFVwbG9hZFByb2dyZXNzICgwLTEwMCksIHRvdGFsQnl0ZXMgYW5kIHRvdGFsQnl0ZXNTZW50XG4gICAgdG90YWx1cGxvYWRwcm9ncmVzcyAoKSB7XG4gICAgfSxcbiAgICAvLyBDYWxsZWQganVzdCBiZWZvcmUgdGhlIGZpbGUgaXMgc2VudC4gR2V0cyB0aGUgYHhocmAgb2JqZWN0IGFzIHNlY29uZFxuICAgIC8vIHBhcmFtZXRlciwgc28geW91IGNhbiBtb2RpZnkgaXQgKGZvciBleGFtcGxlIHRvIGFkZCBhIENTUkYgdG9rZW4pIGFuZCBhXG4gICAgLy8gYGZvcm1EYXRhYCBvYmplY3QgdG8gYWRkIGFkZGl0aW9uYWwgaW5mb3JtYXRpb24uXG4gICAgc2VuZGluZyAoKSB7XG4gICAgfSxcbiAgICBzZW5kaW5nbXVsdGlwbGUgKCkge1xuICAgIH0sXG4gICAgLy8gV2hlbiB0aGUgY29tcGxldGUgdXBsb2FkIGlzIGZpbmlzaGVkIGFuZCBzdWNjZXNzZnVsXG4gICAgLy8gUmVjZWl2ZXMgYGZpbGVgXG4gICAgc3VjY2VzcyAoZmlsZSkge1xuICAgICAgICBpZiAoZmlsZS5wcmV2aWV3RWxlbWVudCkgcmV0dXJuIGZpbGUucHJldmlld0VsZW1lbnQuY2xhc3NMaXN0LmFkZChcImR6LXN1Y2Nlc3NcIik7XG4gICAgfSxcbiAgICBzdWNjZXNzbXVsdGlwbGUgKCkge1xuICAgIH0sXG4gICAgLy8gV2hlbiB0aGUgdXBsb2FkIGlzIGNhbmNlbGVkLlxuICAgIGNhbmNlbGVkIChmaWxlKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmVtaXQoXCJlcnJvclwiLCBmaWxlLCB0aGlzLm9wdGlvbnMuZGljdFVwbG9hZENhbmNlbGVkKTtcbiAgICB9LFxuICAgIGNhbmNlbGVkbXVsdGlwbGUgKCkge1xuICAgIH0sXG4gICAgLy8gV2hlbiB0aGUgdXBsb2FkIGlzIGZpbmlzaGVkLCBlaXRoZXIgd2l0aCBzdWNjZXNzIG9yIGFuIGVycm9yLlxuICAgIC8vIFJlY2VpdmVzIGBmaWxlYFxuICAgIGNvbXBsZXRlIChmaWxlKSB7XG4gICAgICAgIGlmIChmaWxlLl9yZW1vdmVMaW5rKSBmaWxlLl9yZW1vdmVMaW5rLmlubmVySFRNTCA9IHRoaXMub3B0aW9ucy5kaWN0UmVtb3ZlRmlsZTtcbiAgICAgICAgaWYgKGZpbGUucHJldmlld0VsZW1lbnQpIHJldHVybiBmaWxlLnByZXZpZXdFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1jb21wbGV0ZVwiKTtcbiAgICB9LFxuICAgIGNvbXBsZXRlbXVsdGlwbGUgKCkge1xuICAgIH0sXG4gICAgbWF4ZmlsZXNleGNlZWRlZCAoKSB7XG4gICAgfSxcbiAgICBtYXhmaWxlc3JlYWNoZWQgKCkge1xuICAgIH0sXG4gICAgcXVldWVjb21wbGV0ZSAoKSB7XG4gICAgfSxcbiAgICBhZGRlZGZpbGVzICgpIHtcbiAgICB9XG59O1xudmFyICQ0Y2EzNjcxODI3NzZmODBiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkgPSAkNGNhMzY3MTgyNzc2ZjgwYiR2YXIkZGVmYXVsdE9wdGlvbnM7XG5cblxuY2xhc3MgJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOSBleHRlbmRzICQ0MDQwYWNmZDg1ODQzMzhkJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkge1xuICAgIHN0YXRpYyBpbml0Q2xhc3MoKSB7XG4gICAgICAgIC8vIEV4cG9zaW5nIHRoZSBlbWl0dGVyIGNsYXNzLCBtYWlubHkgZm9yIHRlc3RzXG4gICAgICAgIHRoaXMucHJvdG90eXBlLkVtaXR0ZXIgPSAkNDA0MGFjZmQ4NTg0MzM4ZCRleHBvcnQkMmUyYmNkODczOWFlMDM5O1xuICAgICAgICAvKlxuICAgICBUaGlzIGlzIGEgbGlzdCBvZiBhbGwgYXZhaWxhYmxlIGV2ZW50cyB5b3UgY2FuIHJlZ2lzdGVyIG9uIGEgZHJvcHpvbmUgb2JqZWN0LlxuXG4gICAgIFlvdSBjYW4gcmVnaXN0ZXIgYW4gZXZlbnQgaGFuZGxlciBsaWtlIHRoaXM6XG5cbiAgICAgZHJvcHpvbmUub24oXCJkcmFnRW50ZXJcIiwgZnVuY3Rpb24oKSB7IH0pO1xuXG4gICAgICovIHRoaXMucHJvdG90eXBlLmV2ZW50cyA9IFtcbiAgICAgICAgICAgIFwiZHJvcFwiLFxuICAgICAgICAgICAgXCJkcmFnc3RhcnRcIixcbiAgICAgICAgICAgIFwiZHJhZ2VuZFwiLFxuICAgICAgICAgICAgXCJkcmFnZW50ZXJcIixcbiAgICAgICAgICAgIFwiZHJhZ292ZXJcIixcbiAgICAgICAgICAgIFwiZHJhZ2xlYXZlXCIsXG4gICAgICAgICAgICBcImFkZGVkZmlsZVwiLFxuICAgICAgICAgICAgXCJhZGRlZGZpbGVzXCIsXG4gICAgICAgICAgICBcInJlbW92ZWRmaWxlXCIsXG4gICAgICAgICAgICBcInRodW1ibmFpbFwiLFxuICAgICAgICAgICAgXCJlcnJvclwiLFxuICAgICAgICAgICAgXCJlcnJvcm11bHRpcGxlXCIsXG4gICAgICAgICAgICBcInByb2Nlc3NpbmdcIixcbiAgICAgICAgICAgIFwicHJvY2Vzc2luZ211bHRpcGxlXCIsXG4gICAgICAgICAgICBcInVwbG9hZHByb2dyZXNzXCIsXG4gICAgICAgICAgICBcInRvdGFsdXBsb2FkcHJvZ3Jlc3NcIixcbiAgICAgICAgICAgIFwic2VuZGluZ1wiLFxuICAgICAgICAgICAgXCJzZW5kaW5nbXVsdGlwbGVcIixcbiAgICAgICAgICAgIFwic3VjY2Vzc1wiLFxuICAgICAgICAgICAgXCJzdWNjZXNzbXVsdGlwbGVcIixcbiAgICAgICAgICAgIFwiY2FuY2VsZWRcIixcbiAgICAgICAgICAgIFwiY2FuY2VsZWRtdWx0aXBsZVwiLFxuICAgICAgICAgICAgXCJjb21wbGV0ZVwiLFxuICAgICAgICAgICAgXCJjb21wbGV0ZW11bHRpcGxlXCIsXG4gICAgICAgICAgICBcInJlc2V0XCIsXG4gICAgICAgICAgICBcIm1heGZpbGVzZXhjZWVkZWRcIixcbiAgICAgICAgICAgIFwibWF4ZmlsZXNyZWFjaGVkXCIsXG4gICAgICAgICAgICBcInF1ZXVlY29tcGxldGVcIiwgXG4gICAgICAgIF07XG4gICAgICAgIHRoaXMucHJvdG90eXBlLl90aHVtYm5haWxRdWV1ZSA9IFtdO1xuICAgICAgICB0aGlzLnByb3RvdHlwZS5fcHJvY2Vzc2luZ1RodW1ibmFpbCA9IGZhbHNlO1xuICAgIH1cbiAgICAvLyBSZXR1cm5zIGFsbCBmaWxlcyB0aGF0IGhhdmUgYmVlbiBhY2NlcHRlZFxuICAgIGdldEFjY2VwdGVkRmlsZXMoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmZpbGVzLmZpbHRlcigoZmlsZSk9PmZpbGUuYWNjZXB0ZWRcbiAgICAgICAgKS5tYXAoKGZpbGUpPT5maWxlXG4gICAgICAgICk7XG4gICAgfVxuICAgIC8vIFJldHVybnMgYWxsIGZpbGVzIHRoYXQgaGF2ZSBiZWVuIHJlamVjdGVkXG4gICAgLy8gTm90IHN1cmUgd2hlbiB0aGF0J3MgZ29pbmcgdG8gYmUgdXNlZnVsLCBidXQgYWRkZWQgZm9yIGNvbXBsZXRlbmVzcy5cbiAgICBnZXRSZWplY3RlZEZpbGVzKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5maWxlcy5maWx0ZXIoKGZpbGUpPT4hZmlsZS5hY2NlcHRlZFxuICAgICAgICApLm1hcCgoZmlsZSk9PmZpbGVcbiAgICAgICAgKTtcbiAgICB9XG4gICAgZ2V0RmlsZXNXaXRoU3RhdHVzKHN0YXR1cykge1xuICAgICAgICByZXR1cm4gdGhpcy5maWxlcy5maWx0ZXIoKGZpbGUpPT5maWxlLnN0YXR1cyA9PT0gc3RhdHVzXG4gICAgICAgICkubWFwKChmaWxlKT0+ZmlsZVxuICAgICAgICApO1xuICAgIH1cbiAgICAvLyBSZXR1cm5zIGFsbCBmaWxlcyB0aGF0IGFyZSBpbiB0aGUgcXVldWVcbiAgICBnZXRRdWV1ZWRGaWxlcygpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZ2V0RmlsZXNXaXRoU3RhdHVzKCQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuUVVFVUVEKTtcbiAgICB9XG4gICAgZ2V0VXBsb2FkaW5nRmlsZXMoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmdldEZpbGVzV2l0aFN0YXR1cygkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LlVQTE9BRElORyk7XG4gICAgfVxuICAgIGdldEFkZGVkRmlsZXMoKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmdldEZpbGVzV2l0aFN0YXR1cygkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkFEREVEKTtcbiAgICB9XG4gICAgLy8gRmlsZXMgdGhhdCBhcmUgZWl0aGVyIHF1ZXVlZCBvciB1cGxvYWRpbmdcbiAgICBnZXRBY3RpdmVGaWxlcygpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuZmlsZXMuZmlsdGVyKChmaWxlKT0+ZmlsZS5zdGF0dXMgPT09ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuVVBMT0FESU5HIHx8IGZpbGUuc3RhdHVzID09PSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LlFVRVVFRFxuICAgICAgICApLm1hcCgoZmlsZSk9PmZpbGVcbiAgICAgICAgKTtcbiAgICB9XG4gICAgLy8gVGhlIGZ1bmN0aW9uIHRoYXQgZ2V0cyBjYWxsZWQgd2hlbiBEcm9wem9uZSBpcyBpbml0aWFsaXplZC4gWW91XG4gICAgLy8gY2FuIChhbmQgc2hvdWxkKSBzZXR1cCBldmVudCBsaXN0ZW5lcnMgaW5zaWRlIHRoaXMgZnVuY3Rpb24uXG4gICAgaW5pdCgpIHtcbiAgICAgICAgLy8gSW4gY2FzZSBpdCBpc24ndCBzZXQgYWxyZWFkeVxuICAgICAgICBpZiAodGhpcy5lbGVtZW50LnRhZ05hbWUgPT09IFwiZm9ybVwiKSB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKFwiZW5jdHlwZVwiLCBcIm11bHRpcGFydC9mb3JtLWRhdGFcIik7XG4gICAgICAgIGlmICh0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LmNvbnRhaW5zKFwiZHJvcHpvbmVcIikgJiYgIXRoaXMuZWxlbWVudC5xdWVyeVNlbGVjdG9yKFwiLmR6LW1lc3NhZ2VcIikpIHRoaXMuZWxlbWVudC5hcHBlbmRDaGlsZCgkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmNyZWF0ZUVsZW1lbnQoYDxkaXYgY2xhc3M9XCJkei1kZWZhdWx0IGR6LW1lc3NhZ2VcIj48YnV0dG9uIGNsYXNzPVwiZHotYnV0dG9uXCIgdHlwZT1cImJ1dHRvblwiPiR7dGhpcy5vcHRpb25zLmRpY3REZWZhdWx0TWVzc2FnZX08L2J1dHRvbj48L2Rpdj5gKSk7XG4gICAgICAgIGlmICh0aGlzLmNsaWNrYWJsZUVsZW1lbnRzLmxlbmd0aCkge1xuICAgICAgICAgICAgbGV0IHNldHVwSGlkZGVuRmlsZUlucHV0ID0gKCk9PntcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5oaWRkZW5GaWxlSW5wdXQpIHRoaXMuaGlkZGVuRmlsZUlucHV0LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQodGhpcy5oaWRkZW5GaWxlSW5wdXQpO1xuICAgICAgICAgICAgICAgIHRoaXMuaGlkZGVuRmlsZUlucHV0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImlucHV0XCIpO1xuICAgICAgICAgICAgICAgIHRoaXMuaGlkZGVuRmlsZUlucHV0LnNldEF0dHJpYnV0ZShcInR5cGVcIiwgXCJmaWxlXCIpO1xuICAgICAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMubWF4RmlsZXMgPT09IG51bGwgfHwgdGhpcy5vcHRpb25zLm1heEZpbGVzID4gMSkgdGhpcy5oaWRkZW5GaWxlSW5wdXQuc2V0QXR0cmlidXRlKFwibXVsdGlwbGVcIiwgXCJtdWx0aXBsZVwiKTtcbiAgICAgICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5jbGFzc05hbWUgPSBcImR6LWhpZGRlbi1pbnB1dFwiO1xuICAgICAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMuYWNjZXB0ZWRGaWxlcyAhPT0gbnVsbCkgdGhpcy5oaWRkZW5GaWxlSW5wdXQuc2V0QXR0cmlidXRlKFwiYWNjZXB0XCIsIHRoaXMub3B0aW9ucy5hY2NlcHRlZEZpbGVzKTtcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLmNhcHR1cmUgIT09IG51bGwpIHRoaXMuaGlkZGVuRmlsZUlucHV0LnNldEF0dHJpYnV0ZShcImNhcHR1cmVcIiwgdGhpcy5vcHRpb25zLmNhcHR1cmUpO1xuICAgICAgICAgICAgICAgIC8vIE1ha2luZyBzdXJlIHRoYXQgbm8gb25lIGNhbiBcInRhYlwiIGludG8gdGhpcyBmaWVsZC5cbiAgICAgICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5zZXRBdHRyaWJ1dGUoXCJ0YWJpbmRleFwiLCBcIi0xXCIpO1xuICAgICAgICAgICAgICAgIC8vIE5vdCBzZXR0aW5nIGBkaXNwbGF5PVwibm9uZVwiYCBiZWNhdXNlIHNvbWUgYnJvd3NlcnMgZG9uJ3QgYWNjZXB0IGNsaWNrc1xuICAgICAgICAgICAgICAgIC8vIG9uIGVsZW1lbnRzIHRoYXQgYXJlbid0IGRpc3BsYXllZC5cbiAgICAgICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5zdHlsZS52aXNpYmlsaXR5ID0gXCJoaWRkZW5cIjtcbiAgICAgICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5zdHlsZS5wb3NpdGlvbiA9IFwiYWJzb2x1dGVcIjtcbiAgICAgICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5zdHlsZS50b3AgPSBcIjBcIjtcbiAgICAgICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5zdHlsZS5sZWZ0ID0gXCIwXCI7XG4gICAgICAgICAgICAgICAgdGhpcy5oaWRkZW5GaWxlSW5wdXQuc3R5bGUuaGVpZ2h0ID0gXCIwXCI7XG4gICAgICAgICAgICAgICAgdGhpcy5oaWRkZW5GaWxlSW5wdXQuc3R5bGUud2lkdGggPSBcIjBcIjtcbiAgICAgICAgICAgICAgICAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmdldEVsZW1lbnQodGhpcy5vcHRpb25zLmhpZGRlbklucHV0Q29udGFpbmVyLCBcImhpZGRlbklucHV0Q29udGFpbmVyXCIpLmFwcGVuZENoaWxkKHRoaXMuaGlkZGVuRmlsZUlucHV0KTtcbiAgICAgICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwiY2hhbmdlXCIsICgpPT57XG4gICAgICAgICAgICAgICAgICAgIGxldCB7IGZpbGVzOiBmaWxlcyAgfSA9IHRoaXMuaGlkZGVuRmlsZUlucHV0O1xuICAgICAgICAgICAgICAgICAgICBpZiAoZmlsZXMubGVuZ3RoKSBmb3IgKGxldCBmaWxlIG9mIGZpbGVzKXRoaXMuYWRkRmlsZShmaWxlKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5lbWl0KFwiYWRkZWRmaWxlc1wiLCBmaWxlcyk7XG4gICAgICAgICAgICAgICAgICAgIHNldHVwSGlkZGVuRmlsZUlucHV0KCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgICAgc2V0dXBIaWRkZW5GaWxlSW5wdXQoKTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLlVSTCA9IHdpbmRvdy5VUkwgIT09IG51bGwgPyB3aW5kb3cuVVJMIDogd2luZG93LndlYmtpdFVSTDtcbiAgICAgICAgLy8gU2V0dXAgYWxsIGV2ZW50IGxpc3RlbmVycyBvbiB0aGUgRHJvcHpvbmUgb2JqZWN0IGl0c2VsZi5cbiAgICAgICAgLy8gVGhleSdyZSBub3QgaW4gQHNldHVwRXZlbnRMaXN0ZW5lcnMoKSBiZWNhdXNlIHRoZXkgc2hvdWxkbid0IGJlIHJlbW92ZWRcbiAgICAgICAgLy8gYWdhaW4gd2hlbiB0aGUgZHJvcHpvbmUgZ2V0cyBkaXNhYmxlZC5cbiAgICAgICAgZm9yIChsZXQgZXZlbnROYW1lIG9mIHRoaXMuZXZlbnRzKXRoaXMub24oZXZlbnROYW1lLCB0aGlzLm9wdGlvbnNbZXZlbnROYW1lXSk7XG4gICAgICAgIHRoaXMub24oXCJ1cGxvYWRwcm9ncmVzc1wiLCAoKT0+dGhpcy51cGRhdGVUb3RhbFVwbG9hZFByb2dyZXNzKClcbiAgICAgICAgKTtcbiAgICAgICAgdGhpcy5vbihcInJlbW92ZWRmaWxlXCIsICgpPT50aGlzLnVwZGF0ZVRvdGFsVXBsb2FkUHJvZ3Jlc3MoKVxuICAgICAgICApO1xuICAgICAgICB0aGlzLm9uKFwiY2FuY2VsZWRcIiwgKGZpbGUpPT50aGlzLmVtaXQoXCJjb21wbGV0ZVwiLCBmaWxlKVxuICAgICAgICApO1xuICAgICAgICAvLyBFbWl0IGEgYHF1ZXVlY29tcGxldGVgIGV2ZW50IGlmIGFsbCBmaWxlcyBmaW5pc2hlZCB1cGxvYWRpbmcuXG4gICAgICAgIHRoaXMub24oXCJjb21wbGV0ZVwiLCAoZmlsZSk9PntcbiAgICAgICAgICAgIGlmICh0aGlzLmdldEFkZGVkRmlsZXMoKS5sZW5ndGggPT09IDAgJiYgdGhpcy5nZXRVcGxvYWRpbmdGaWxlcygpLmxlbmd0aCA9PT0gMCAmJiB0aGlzLmdldFF1ZXVlZEZpbGVzKCkubGVuZ3RoID09PSAwKSAvLyBUaGlzIG5lZWRzIHRvIGJlIGRlZmVycmVkIHNvIHRoYXQgYHF1ZXVlY29tcGxldGVgIHJlYWxseSB0cmlnZ2VycyBhZnRlciBgY29tcGxldGVgXG4gICAgICAgICAgICByZXR1cm4gc2V0VGltZW91dCgoKT0+dGhpcy5lbWl0KFwicXVldWVjb21wbGV0ZVwiKVxuICAgICAgICAgICAgLCAwKTtcbiAgICAgICAgfSk7XG4gICAgICAgIGNvbnN0IGNvbnRhaW5zRmlsZXMgPSBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICBpZiAoZS5kYXRhVHJhbnNmZXIudHlwZXMpIC8vIEJlY2F1c2UgZS5kYXRhVHJhbnNmZXIudHlwZXMgaXMgYW4gT2JqZWN0IGluXG4gICAgICAgICAgICAvLyBJRSwgd2UgbmVlZCB0byBpdGVyYXRlIGxpa2UgdGhpcyBpbnN0ZWFkIG9mXG4gICAgICAgICAgICAvLyB1c2luZyBlLmRhdGFUcmFuc2Zlci50eXBlcy5zb21lKClcbiAgICAgICAgICAgIGZvcih2YXIgaSA9IDA7IGkgPCBlLmRhdGFUcmFuc2Zlci50eXBlcy5sZW5ndGg7IGkrKyl7XG4gICAgICAgICAgICAgICAgaWYgKGUuZGF0YVRyYW5zZmVyLnR5cGVzW2ldID09PSBcIkZpbGVzXCIpIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9O1xuICAgICAgICBsZXQgbm9Qcm9wYWdhdGlvbiA9IGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIC8vIElmIHRoZXJlIGFyZSBubyBmaWxlcywgd2UgZG9uJ3Qgd2FudCB0byBzdG9wXG4gICAgICAgICAgICAvLyBwcm9wYWdhdGlvbiBzbyB3ZSBkb24ndCBpbnRlcmZlcmUgd2l0aCBvdGhlclxuICAgICAgICAgICAgLy8gZHJhZyBhbmQgZHJvcCBiZWhhdmlvdXIuXG4gICAgICAgICAgICBpZiAoIWNvbnRhaW5zRmlsZXMoZSkpIHJldHVybjtcbiAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICAgICAgICBpZiAoZS5wcmV2ZW50RGVmYXVsdCkgcmV0dXJuIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIGVsc2UgcmV0dXJuIGUucmV0dXJuVmFsdWUgPSBmYWxzZTtcbiAgICAgICAgfTtcbiAgICAgICAgLy8gQ3JlYXRlIHRoZSBsaXN0ZW5lcnNcbiAgICAgICAgdGhpcy5saXN0ZW5lcnMgPSBbXG4gICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgZWxlbWVudDogdGhpcy5lbGVtZW50LFxuICAgICAgICAgICAgICAgIGV2ZW50czoge1xuICAgICAgICAgICAgICAgICAgICBkcmFnc3RhcnQ6IChlKT0+e1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuZW1pdChcImRyYWdzdGFydFwiLCBlKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZHJhZ2VudGVyOiAoZSk9PntcbiAgICAgICAgICAgICAgICAgICAgICAgIG5vUHJvcGFnYXRpb24oZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5lbWl0KFwiZHJhZ2VudGVyXCIsIGUpO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBkcmFnb3ZlcjogKGUpPT57XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBNYWtlcyBpdCBwb3NzaWJsZSB0byBkcmFnIGZpbGVzIGZyb20gY2hyb21lJ3MgZG93bmxvYWQgYmFyXG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBodHRwOi8vc3RhY2tvdmVyZmxvdy5jb20vcXVlc3Rpb25zLzE5NTI2NDMwL2RyYWctYW5kLWRyb3AtZmlsZS11cGxvYWRzLWZyb20tY2hyb21lLWRvd25sb2Fkcy1iYXJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIFRyeSBpcyByZXF1aXJlZCB0byBwcmV2ZW50IGJ1ZyBpbiBJbnRlcm5ldCBFeHBsb3JlciAxMSAoU0NSSVBUNjU1MzUgZXhjZXB0aW9uKVxuICAgICAgICAgICAgICAgICAgICAgICAgbGV0IGVmY3Q7XG4gICAgICAgICAgICAgICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVmY3QgPSBlLmRhdGFUcmFuc2Zlci5lZmZlY3RBbGxvd2VkO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBjYXRjaCAoZXJyb3IpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIGUuZGF0YVRyYW5zZmVyLmRyb3BFZmZlY3QgPSBcIm1vdmVcIiA9PT0gZWZjdCB8fCBcImxpbmtNb3ZlXCIgPT09IGVmY3QgPyBcIm1vdmVcIiA6IFwiY29weVwiO1xuICAgICAgICAgICAgICAgICAgICAgICAgbm9Qcm9wYWdhdGlvbihlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmVtaXQoXCJkcmFnb3ZlclwiLCBlKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZHJhZ2xlYXZlOiAoZSk9PntcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmVtaXQoXCJkcmFnbGVhdmVcIiwgZSk7XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIGRyb3A6IChlKT0+e1xuICAgICAgICAgICAgICAgICAgICAgICAgbm9Qcm9wYWdhdGlvbihlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLmRyb3AoZSk7XG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIGRyYWdlbmQ6IChlKT0+e1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuZW1pdChcImRyYWdlbmRcIiwgZSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9LCBcbiAgICAgICAgXTtcbiAgICAgICAgdGhpcy5jbGlja2FibGVFbGVtZW50cy5mb3JFYWNoKChjbGlja2FibGVFbGVtZW50KT0+e1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMubGlzdGVuZXJzLnB1c2goe1xuICAgICAgICAgICAgICAgIGVsZW1lbnQ6IGNsaWNrYWJsZUVsZW1lbnQsXG4gICAgICAgICAgICAgICAgZXZlbnRzOiB7XG4gICAgICAgICAgICAgICAgICAgIGNsaWNrOiAoZXZ0KT0+e1xuICAgICAgICAgICAgICAgICAgICAgICAgLy8gT25seSB0aGUgYWN0dWFsIGRyb3B6b25lIG9yIHRoZSBtZXNzYWdlIGVsZW1lbnQgc2hvdWxkIHRyaWdnZXIgZmlsZSBzZWxlY3Rpb25cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChjbGlja2FibGVFbGVtZW50ICE9PSB0aGlzLmVsZW1lbnQgfHwgZXZ0LnRhcmdldCA9PT0gdGhpcy5lbGVtZW50IHx8ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuZWxlbWVudEluc2lkZShldnQudGFyZ2V0LCB0aGlzLmVsZW1lbnQucXVlcnlTZWxlY3RvcihcIi5kei1tZXNzYWdlXCIpKSkgdGhpcy5oaWRkZW5GaWxlSW5wdXQuY2xpY2soKTsgLy8gRm9yd2FyZCB0aGUgY2xpY2tcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgICAgICB0aGlzLmVuYWJsZSgpO1xuICAgICAgICByZXR1cm4gdGhpcy5vcHRpb25zLmluaXQuY2FsbCh0aGlzKTtcbiAgICB9XG4gICAgLy8gTm90IGZ1bGx5IHRlc3RlZCB5ZXRcbiAgICBkZXN0cm95KCkge1xuICAgICAgICB0aGlzLmRpc2FibGUoKTtcbiAgICAgICAgdGhpcy5yZW1vdmVBbGxGaWxlcyh0cnVlKTtcbiAgICAgICAgaWYgKHRoaXMuaGlkZGVuRmlsZUlucHV0ICE9IG51bGwgPyB0aGlzLmhpZGRlbkZpbGVJbnB1dC5wYXJlbnROb2RlIDogdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICB0aGlzLmhpZGRlbkZpbGVJbnB1dC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHRoaXMuaGlkZGVuRmlsZUlucHV0KTtcbiAgICAgICAgICAgIHRoaXMuaGlkZGVuRmlsZUlucHV0ID0gbnVsbDtcbiAgICAgICAgfVxuICAgICAgICBkZWxldGUgdGhpcy5lbGVtZW50LmRyb3B6b25lO1xuICAgICAgICByZXR1cm4gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5pbnN0YW5jZXMuc3BsaWNlKCQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuaW5zdGFuY2VzLmluZGV4T2YodGhpcyksIDEpO1xuICAgIH1cbiAgICB1cGRhdGVUb3RhbFVwbG9hZFByb2dyZXNzKCkge1xuICAgICAgICBsZXQgdG90YWxVcGxvYWRQcm9ncmVzcztcbiAgICAgICAgbGV0IHRvdGFsQnl0ZXNTZW50ID0gMDtcbiAgICAgICAgbGV0IHRvdGFsQnl0ZXMgPSAwO1xuICAgICAgICBsZXQgYWN0aXZlRmlsZXMgPSB0aGlzLmdldEFjdGl2ZUZpbGVzKCk7XG4gICAgICAgIGlmIChhY3RpdmVGaWxlcy5sZW5ndGgpIHtcbiAgICAgICAgICAgIGZvciAobGV0IGZpbGUgb2YgdGhpcy5nZXRBY3RpdmVGaWxlcygpKXtcbiAgICAgICAgICAgICAgICB0b3RhbEJ5dGVzU2VudCArPSBmaWxlLnVwbG9hZC5ieXRlc1NlbnQ7XG4gICAgICAgICAgICAgICAgdG90YWxCeXRlcyArPSBmaWxlLnVwbG9hZC50b3RhbDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRvdGFsVXBsb2FkUHJvZ3Jlc3MgPSAxMDAgKiB0b3RhbEJ5dGVzU2VudCAvIHRvdGFsQnl0ZXM7XG4gICAgICAgIH0gZWxzZSB0b3RhbFVwbG9hZFByb2dyZXNzID0gMTAwO1xuICAgICAgICByZXR1cm4gdGhpcy5lbWl0KFwidG90YWx1cGxvYWRwcm9ncmVzc1wiLCB0b3RhbFVwbG9hZFByb2dyZXNzLCB0b3RhbEJ5dGVzLCB0b3RhbEJ5dGVzU2VudCk7XG4gICAgfVxuICAgIC8vIEBvcHRpb25zLnBhcmFtTmFtZSBjYW4gYmUgYSBmdW5jdGlvbiB0YWtpbmcgb25lIHBhcmFtZXRlciByYXRoZXIgdGhhbiBhIHN0cmluZy5cbiAgICAvLyBBIHBhcmFtZXRlciBuYW1lIGZvciBhIGZpbGUgaXMgb2J0YWluZWQgc2ltcGx5IGJ5IGNhbGxpbmcgdGhpcyB3aXRoIGFuIGluZGV4IG51bWJlci5cbiAgICBfZ2V0UGFyYW1OYW1lKG4pIHtcbiAgICAgICAgaWYgKHR5cGVvZiB0aGlzLm9wdGlvbnMucGFyYW1OYW1lID09PSBcImZ1bmN0aW9uXCIpIHJldHVybiB0aGlzLm9wdGlvbnMucGFyYW1OYW1lKG4pO1xuICAgICAgICBlbHNlIHJldHVybiBgJHt0aGlzLm9wdGlvbnMucGFyYW1OYW1lfSR7dGhpcy5vcHRpb25zLnVwbG9hZE11bHRpcGxlID8gYFske259XWAgOiBcIlwifWA7XG4gICAgfVxuICAgIC8vIElmIEBvcHRpb25zLnJlbmFtZUZpbGUgaXMgYSBmdW5jdGlvbixcbiAgICAvLyB0aGUgZnVuY3Rpb24gd2lsbCBiZSB1c2VkIHRvIHJlbmFtZSB0aGUgZmlsZS5uYW1lIGJlZm9yZSBhcHBlbmRpbmcgaXQgdG8gdGhlIGZvcm1EYXRhXG4gICAgX3JlbmFtZUZpbGUoZmlsZSkge1xuICAgICAgICBpZiAodHlwZW9mIHRoaXMub3B0aW9ucy5yZW5hbWVGaWxlICE9PSBcImZ1bmN0aW9uXCIpIHJldHVybiBmaWxlLm5hbWU7XG4gICAgICAgIHJldHVybiB0aGlzLm9wdGlvbnMucmVuYW1lRmlsZShmaWxlKTtcbiAgICB9XG4gICAgLy8gUmV0dXJucyBhIGZvcm0gdGhhdCBjYW4gYmUgdXNlZCBhcyBmYWxsYmFjayBpZiB0aGUgYnJvd3NlciBkb2VzIG5vdCBzdXBwb3J0IERyYWduRHJvcFxuICAgIC8vXG4gICAgLy8gSWYgdGhlIGRyb3B6b25lIGlzIGFscmVhZHkgYSBmb3JtLCBvbmx5IHRoZSBpbnB1dCBmaWVsZCBhbmQgYnV0dG9uIGFyZSByZXR1cm5lZC4gT3RoZXJ3aXNlIGEgY29tcGxldGUgZm9ybSBlbGVtZW50IGlzIHByb3ZpZGVkLlxuICAgIC8vIFRoaXMgY29kZSBoYXMgdG8gcGFzcyBpbiBJRTcgOihcbiAgICBnZXRGYWxsYmFja0Zvcm0oKSB7XG4gICAgICAgIGxldCBleGlzdGluZ0ZhbGxiYWNrLCBmb3JtO1xuICAgICAgICBpZiAoZXhpc3RpbmdGYWxsYmFjayA9IHRoaXMuZ2V0RXhpc3RpbmdGYWxsYmFjaygpKSByZXR1cm4gZXhpc3RpbmdGYWxsYmFjaztcbiAgICAgICAgbGV0IGZpZWxkc1N0cmluZyA9ICc8ZGl2IGNsYXNzPVwiZHotZmFsbGJhY2tcIj4nO1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLmRpY3RGYWxsYmFja1RleHQpIGZpZWxkc1N0cmluZyArPSBgPHA+JHt0aGlzLm9wdGlvbnMuZGljdEZhbGxiYWNrVGV4dH08L3A+YDtcbiAgICAgICAgZmllbGRzU3RyaW5nICs9IGA8aW5wdXQgdHlwZT1cImZpbGVcIiBuYW1lPVwiJHt0aGlzLl9nZXRQYXJhbU5hbWUoMCl9XCIgJHt0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUgPyAnbXVsdGlwbGU9XCJtdWx0aXBsZVwiJyA6IHVuZGVmaW5lZH0gLz48aW5wdXQgdHlwZT1cInN1Ym1pdFwiIHZhbHVlPVwiVXBsb2FkIVwiPjwvZGl2PmA7XG4gICAgICAgIGxldCBmaWVsZHMgPSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmNyZWF0ZUVsZW1lbnQoZmllbGRzU3RyaW5nKTtcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudC50YWdOYW1lICE9PSBcIkZPUk1cIikge1xuICAgICAgICAgICAgZm9ybSA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuY3JlYXRlRWxlbWVudChgPGZvcm0gYWN0aW9uPVwiJHt0aGlzLm9wdGlvbnMudXJsfVwiIGVuY3R5cGU9XCJtdWx0aXBhcnQvZm9ybS1kYXRhXCIgbWV0aG9kPVwiJHt0aGlzLm9wdGlvbnMubWV0aG9kfVwiPjwvZm9ybT5gKTtcbiAgICAgICAgICAgIGZvcm0uYXBwZW5kQ2hpbGQoZmllbGRzKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIC8vIE1ha2Ugc3VyZSB0aGF0IHRoZSBlbmN0eXBlIGFuZCBtZXRob2QgYXR0cmlidXRlcyBhcmUgc2V0IHByb3Blcmx5XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKFwiZW5jdHlwZVwiLCBcIm11bHRpcGFydC9mb3JtLWRhdGFcIik7XG4gICAgICAgICAgICB0aGlzLmVsZW1lbnQuc2V0QXR0cmlidXRlKFwibWV0aG9kXCIsIHRoaXMub3B0aW9ucy5tZXRob2QpO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBmb3JtICE9IG51bGwgPyBmb3JtIDogZmllbGRzO1xuICAgIH1cbiAgICAvLyBSZXR1cm5zIHRoZSBmYWxsYmFjayBlbGVtZW50cyBpZiB0aGV5IGV4aXN0IGFscmVhZHlcbiAgICAvL1xuICAgIC8vIFRoaXMgY29kZSBoYXMgdG8gcGFzcyBpbiBJRTcgOihcbiAgICBnZXRFeGlzdGluZ0ZhbGxiYWNrKCkge1xuICAgICAgICBsZXQgZ2V0RmFsbGJhY2sgPSBmdW5jdGlvbihlbGVtZW50cykge1xuICAgICAgICAgICAgZm9yIChsZXQgZWwgb2YgZWxlbWVudHMpe1xuICAgICAgICAgICAgICAgIGlmICgvKF58IClmYWxsYmFjaygkfCApLy50ZXN0KGVsLmNsYXNzTmFtZSkpIHJldHVybiBlbDtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgZm9yIChsZXQgdGFnTmFtZSBvZiBbXG4gICAgICAgICAgICBcImRpdlwiLFxuICAgICAgICAgICAgXCJmb3JtXCJcbiAgICAgICAgXSl7XG4gICAgICAgICAgICB2YXIgZmFsbGJhY2s7XG4gICAgICAgICAgICBpZiAoZmFsbGJhY2sgPSBnZXRGYWxsYmFjayh0aGlzLmVsZW1lbnQuZ2V0RWxlbWVudHNCeVRhZ05hbWUodGFnTmFtZSkpKSByZXR1cm4gZmFsbGJhY2s7XG4gICAgICAgIH1cbiAgICB9XG4gICAgLy8gQWN0aXZhdGVzIGFsbCBsaXN0ZW5lcnMgc3RvcmVkIGluIEBsaXN0ZW5lcnNcbiAgICBzZXR1cEV2ZW50TGlzdGVuZXJzKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5saXN0ZW5lcnMubWFwKChlbGVtZW50TGlzdGVuZXJzKT0+KCgpPT57XG4gICAgICAgICAgICAgICAgbGV0IHJlc3VsdCA9IFtdO1xuICAgICAgICAgICAgICAgIGZvcihsZXQgZXZlbnQgaW4gZWxlbWVudExpc3RlbmVycy5ldmVudHMpe1xuICAgICAgICAgICAgICAgICAgICBsZXQgbGlzdGVuZXIgPSBlbGVtZW50TGlzdGVuZXJzLmV2ZW50c1tldmVudF07XG4gICAgICAgICAgICAgICAgICAgIHJlc3VsdC5wdXNoKGVsZW1lbnRMaXN0ZW5lcnMuZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKGV2ZW50LCBsaXN0ZW5lciwgZmFsc2UpKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlc3VsdDtcbiAgICAgICAgICAgIH0pKClcbiAgICAgICAgKTtcbiAgICB9XG4gICAgLy8gRGVhY3RpdmF0ZXMgYWxsIGxpc3RlbmVycyBzdG9yZWQgaW4gQGxpc3RlbmVyc1xuICAgIHJlbW92ZUV2ZW50TGlzdGVuZXJzKCkge1xuICAgICAgICByZXR1cm4gdGhpcy5saXN0ZW5lcnMubWFwKChlbGVtZW50TGlzdGVuZXJzKT0+KCgpPT57XG4gICAgICAgICAgICAgICAgbGV0IHJlc3VsdCA9IFtdO1xuICAgICAgICAgICAgICAgIGZvcihsZXQgZXZlbnQgaW4gZWxlbWVudExpc3RlbmVycy5ldmVudHMpe1xuICAgICAgICAgICAgICAgICAgICBsZXQgbGlzdGVuZXIgPSBlbGVtZW50TGlzdGVuZXJzLmV2ZW50c1tldmVudF07XG4gICAgICAgICAgICAgICAgICAgIHJlc3VsdC5wdXNoKGVsZW1lbnRMaXN0ZW5lcnMuZWxlbWVudC5yZW1vdmVFdmVudExpc3RlbmVyKGV2ZW50LCBsaXN0ZW5lciwgZmFsc2UpKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlc3VsdDtcbiAgICAgICAgICAgIH0pKClcbiAgICAgICAgKTtcbiAgICB9XG4gICAgLy8gUmVtb3ZlcyBhbGwgZXZlbnQgbGlzdGVuZXJzIGFuZCBjYW5jZWxzIGFsbCBmaWxlcyBpbiB0aGUgcXVldWUgb3IgYmVpbmcgcHJvY2Vzc2VkLlxuICAgIGRpc2FibGUoKSB7XG4gICAgICAgIHRoaXMuY2xpY2thYmxlRWxlbWVudHMuZm9yRWFjaCgoZWxlbWVudCk9PmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcImR6LWNsaWNrYWJsZVwiKVxuICAgICAgICApO1xuICAgICAgICB0aGlzLnJlbW92ZUV2ZW50TGlzdGVuZXJzKCk7XG4gICAgICAgIHRoaXMuZGlzYWJsZWQgPSB0cnVlO1xuICAgICAgICByZXR1cm4gdGhpcy5maWxlcy5tYXAoKGZpbGUpPT50aGlzLmNhbmNlbFVwbG9hZChmaWxlKVxuICAgICAgICApO1xuICAgIH1cbiAgICBlbmFibGUoKSB7XG4gICAgICAgIGRlbGV0ZSB0aGlzLmRpc2FibGVkO1xuICAgICAgICB0aGlzLmNsaWNrYWJsZUVsZW1lbnRzLmZvckVhY2goKGVsZW1lbnQpPT5lbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1jbGlja2FibGVcIilcbiAgICAgICAgKTtcbiAgICAgICAgcmV0dXJuIHRoaXMuc2V0dXBFdmVudExpc3RlbmVycygpO1xuICAgIH1cbiAgICAvLyBSZXR1cm5zIGEgbmljZWx5IGZvcm1hdHRlZCBmaWxlc2l6ZVxuICAgIGZpbGVzaXplKHNpemUpIHtcbiAgICAgICAgbGV0IHNlbGVjdGVkU2l6ZSA9IDA7XG4gICAgICAgIGxldCBzZWxlY3RlZFVuaXQgPSBcImJcIjtcbiAgICAgICAgaWYgKHNpemUgPiAwKSB7XG4gICAgICAgICAgICBsZXQgdW5pdHMgPSBbXG4gICAgICAgICAgICAgICAgXCJ0YlwiLFxuICAgICAgICAgICAgICAgIFwiZ2JcIixcbiAgICAgICAgICAgICAgICBcIm1iXCIsXG4gICAgICAgICAgICAgICAgXCJrYlwiLFxuICAgICAgICAgICAgICAgIFwiYlwiXG4gICAgICAgICAgICBdO1xuICAgICAgICAgICAgZm9yKGxldCBpID0gMDsgaSA8IHVuaXRzLmxlbmd0aDsgaSsrKXtcbiAgICAgICAgICAgICAgICBsZXQgdW5pdCA9IHVuaXRzW2ldO1xuICAgICAgICAgICAgICAgIGxldCBjdXRvZmYgPSBNYXRoLnBvdyh0aGlzLm9wdGlvbnMuZmlsZXNpemVCYXNlLCA0IC0gaSkgLyAxMDtcbiAgICAgICAgICAgICAgICBpZiAoc2l6ZSA+PSBjdXRvZmYpIHtcbiAgICAgICAgICAgICAgICAgICAgc2VsZWN0ZWRTaXplID0gc2l6ZSAvIE1hdGgucG93KHRoaXMub3B0aW9ucy5maWxlc2l6ZUJhc2UsIDQgLSBpKTtcbiAgICAgICAgICAgICAgICAgICAgc2VsZWN0ZWRVbml0ID0gdW5pdDtcbiAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgc2VsZWN0ZWRTaXplID0gTWF0aC5yb3VuZCgxMCAqIHNlbGVjdGVkU2l6ZSkgLyAxMDsgLy8gQ3V0dGluZyBvZiBkaWdpdHNcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gYDxzdHJvbmc+JHtzZWxlY3RlZFNpemV9PC9zdHJvbmc+ICR7dGhpcy5vcHRpb25zLmRpY3RGaWxlU2l6ZVVuaXRzW3NlbGVjdGVkVW5pdF19YDtcbiAgICB9XG4gICAgLy8gQWRkcyBvciByZW1vdmVzIHRoZSBgZHotbWF4LWZpbGVzLXJlYWNoZWRgIGNsYXNzIGZyb20gdGhlIGZvcm0uXG4gICAgX3VwZGF0ZU1heEZpbGVzUmVhY2hlZENsYXNzKCkge1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLm1heEZpbGVzICE9IG51bGwgJiYgdGhpcy5nZXRBY2NlcHRlZEZpbGVzKCkubGVuZ3RoID49IHRoaXMub3B0aW9ucy5tYXhGaWxlcykge1xuICAgICAgICAgICAgaWYgKHRoaXMuZ2V0QWNjZXB0ZWRGaWxlcygpLmxlbmd0aCA9PT0gdGhpcy5vcHRpb25zLm1heEZpbGVzKSB0aGlzLmVtaXQoXCJtYXhmaWxlc3JlYWNoZWRcIiwgdGhpcy5maWxlcyk7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5lbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJkei1tYXgtZmlsZXMtcmVhY2hlZFwiKTtcbiAgICAgICAgfSBlbHNlIHJldHVybiB0aGlzLmVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShcImR6LW1heC1maWxlcy1yZWFjaGVkXCIpO1xuICAgIH1cbiAgICBkcm9wKGUpIHtcbiAgICAgICAgaWYgKCFlLmRhdGFUcmFuc2ZlcikgcmV0dXJuO1xuICAgICAgICB0aGlzLmVtaXQoXCJkcm9wXCIsIGUpO1xuICAgICAgICAvLyBDb252ZXJ0IHRoZSBGaWxlTGlzdCB0byBhbiBBcnJheVxuICAgICAgICAvLyBUaGlzIGlzIG5lY2Vzc2FyeSBmb3IgSUUxMVxuICAgICAgICBsZXQgZmlsZXMgPSBbXTtcbiAgICAgICAgZm9yKGxldCBpID0gMDsgaSA8IGUuZGF0YVRyYW5zZmVyLmZpbGVzLmxlbmd0aDsgaSsrKWZpbGVzW2ldID0gZS5kYXRhVHJhbnNmZXIuZmlsZXNbaV07XG4gICAgICAgIC8vIEV2ZW4gaWYgaXQncyBhIGZvbGRlciwgZmlsZXMubGVuZ3RoIHdpbGwgY29udGFpbiB0aGUgZm9sZGVycy5cbiAgICAgICAgaWYgKGZpbGVzLmxlbmd0aCkge1xuICAgICAgICAgICAgbGV0IHsgaXRlbXM6IGl0ZW1zICB9ID0gZS5kYXRhVHJhbnNmZXI7XG4gICAgICAgICAgICBpZiAoaXRlbXMgJiYgaXRlbXMubGVuZ3RoICYmIGl0ZW1zWzBdLndlYmtpdEdldEFzRW50cnkgIT0gbnVsbCkgLy8gVGhlIGJyb3dzZXIgc3VwcG9ydHMgZHJvcHBpbmcgb2YgZm9sZGVycywgc28gaGFuZGxlIGl0ZW1zIGluc3RlYWQgb2YgZmlsZXNcbiAgICAgICAgICAgIHRoaXMuX2FkZEZpbGVzRnJvbUl0ZW1zKGl0ZW1zKTtcbiAgICAgICAgICAgIGVsc2UgdGhpcy5oYW5kbGVGaWxlcyhmaWxlcyk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5lbWl0KFwiYWRkZWRmaWxlc1wiLCBmaWxlcyk7XG4gICAgfVxuICAgIHBhc3RlKGUpIHtcbiAgICAgICAgaWYgKCQzZWQyNjlmMmYwZmIyMjRiJHZhciRfX2d1YXJkX18oZSAhPSBudWxsID8gZS5jbGlwYm9hcmREYXRhIDogdW5kZWZpbmVkLCAoeCk9PnguaXRlbXNcbiAgICAgICAgKSA9PSBudWxsKSByZXR1cm47XG4gICAgICAgIHRoaXMuZW1pdChcInBhc3RlXCIsIGUpO1xuICAgICAgICBsZXQgeyBpdGVtczogaXRlbXMgIH0gPSBlLmNsaXBib2FyZERhdGE7XG4gICAgICAgIGlmIChpdGVtcy5sZW5ndGgpIHJldHVybiB0aGlzLl9hZGRGaWxlc0Zyb21JdGVtcyhpdGVtcyk7XG4gICAgfVxuICAgIGhhbmRsZUZpbGVzKGZpbGVzKSB7XG4gICAgICAgIGZvciAobGV0IGZpbGUgb2YgZmlsZXMpdGhpcy5hZGRGaWxlKGZpbGUpO1xuICAgIH1cbiAgICAvLyBXaGVuIGEgZm9sZGVyIGlzIGRyb3BwZWQgKG9yIGZpbGVzIGFyZSBwYXN0ZWQpLCBpdGVtcyBtdXN0IGJlIGhhbmRsZWRcbiAgICAvLyBpbnN0ZWFkIG9mIGZpbGVzLlxuICAgIF9hZGRGaWxlc0Zyb21JdGVtcyhpdGVtcykge1xuICAgICAgICByZXR1cm4gKCgpPT57XG4gICAgICAgICAgICBsZXQgcmVzdWx0ID0gW107XG4gICAgICAgICAgICBmb3IgKGxldCBpdGVtIG9mIGl0ZW1zKXtcbiAgICAgICAgICAgICAgICB2YXIgZW50cnk7XG4gICAgICAgICAgICAgICAgaWYgKGl0ZW0ud2Via2l0R2V0QXNFbnRyeSAhPSBudWxsICYmIChlbnRyeSA9IGl0ZW0ud2Via2l0R2V0QXNFbnRyeSgpKSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAoZW50cnkuaXNGaWxlKSByZXN1bHQucHVzaCh0aGlzLmFkZEZpbGUoaXRlbS5nZXRBc0ZpbGUoKSkpO1xuICAgICAgICAgICAgICAgICAgICBlbHNlIGlmIChlbnRyeS5pc0RpcmVjdG9yeSkgLy8gQXBwZW5kIGFsbCBmaWxlcyBmcm9tIHRoYXQgZGlyZWN0b3J5IHRvIGZpbGVzXG4gICAgICAgICAgICAgICAgICAgIHJlc3VsdC5wdXNoKHRoaXMuX2FkZEZpbGVzRnJvbURpcmVjdG9yeShlbnRyeSwgZW50cnkubmFtZSkpO1xuICAgICAgICAgICAgICAgICAgICBlbHNlIHJlc3VsdC5wdXNoKHVuZGVmaW5lZCk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmIChpdGVtLmdldEFzRmlsZSAhPSBudWxsKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChpdGVtLmtpbmQgPT0gbnVsbCB8fCBpdGVtLmtpbmQgPT09IFwiZmlsZVwiKSByZXN1bHQucHVzaCh0aGlzLmFkZEZpbGUoaXRlbS5nZXRBc0ZpbGUoKSkpO1xuICAgICAgICAgICAgICAgICAgICBlbHNlIHJlc3VsdC5wdXNoKHVuZGVmaW5lZCk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHJlc3VsdC5wdXNoKHVuZGVmaW5lZCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gcmVzdWx0O1xuICAgICAgICB9KSgpO1xuICAgIH1cbiAgICAvLyBHb2VzIHRocm91Z2ggdGhlIGRpcmVjdG9yeSwgYW5kIGFkZHMgZWFjaCBmaWxlIGl0IGZpbmRzIHJlY3Vyc2l2ZWx5XG4gICAgX2FkZEZpbGVzRnJvbURpcmVjdG9yeShkaXJlY3RvcnksIHBhdGgpIHtcbiAgICAgICAgbGV0IGRpclJlYWRlciA9IGRpcmVjdG9yeS5jcmVhdGVSZWFkZXIoKTtcbiAgICAgICAgbGV0IGVycm9ySGFuZGxlciA9IChlcnJvcik9PiQzZWQyNjlmMmYwZmIyMjRiJHZhciRfX2d1YXJkTWV0aG9kX18oY29uc29sZSwgXCJsb2dcIiwgKG8pPT5vLmxvZyhlcnJvcilcbiAgICAgICAgICAgIClcbiAgICAgICAgO1xuICAgICAgICB2YXIgcmVhZEVudHJpZXMgPSAoKT0+e1xuICAgICAgICAgICAgcmV0dXJuIGRpclJlYWRlci5yZWFkRW50cmllcygoZW50cmllcyk9PntcbiAgICAgICAgICAgICAgICBpZiAoZW50cmllcy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAgICAgICAgIGZvciAobGV0IGVudHJ5IG9mIGVudHJpZXMpe1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGVudHJ5LmlzRmlsZSkgZW50cnkuZmlsZSgoZmlsZSk9PntcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLmlnbm9yZUhpZGRlbkZpbGVzICYmIGZpbGUubmFtZS5zdWJzdHJpbmcoMCwgMSkgPT09IFwiLlwiKSByZXR1cm47XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZmlsZS5mdWxsUGF0aCA9IGAke3BhdGh9LyR7ZmlsZS5uYW1lfWA7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuYWRkRmlsZShmaWxlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSBpZiAoZW50cnkuaXNEaXJlY3RvcnkpIHRoaXMuX2FkZEZpbGVzRnJvbURpcmVjdG9yeShlbnRyeSwgYCR7cGF0aH0vJHtlbnRyeS5uYW1lfWApO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIC8vIFJlY3Vyc2l2ZWx5IGNhbGwgcmVhZEVudHJpZXMoKSBhZ2Fpbiwgc2luY2UgYnJvd3NlciBvbmx5IGhhbmRsZVxuICAgICAgICAgICAgICAgICAgICAvLyB0aGUgZmlyc3QgMTAwIGVudHJpZXMuXG4gICAgICAgICAgICAgICAgICAgIC8vIFNlZTogaHR0cHM6Ly9kZXZlbG9wZXIubW96aWxsYS5vcmcvZW4tVVMvZG9jcy9XZWIvQVBJL0RpcmVjdG9yeVJlYWRlciNyZWFkRW50cmllc1xuICAgICAgICAgICAgICAgICAgICByZWFkRW50cmllcygpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICByZXR1cm4gbnVsbDtcbiAgICAgICAgICAgIH0sIGVycm9ySGFuZGxlcik7XG4gICAgICAgIH07XG4gICAgICAgIHJldHVybiByZWFkRW50cmllcygpO1xuICAgIH1cbiAgICAvLyBJZiBgZG9uZSgpYCBpcyBjYWxsZWQgd2l0aG91dCBhcmd1bWVudCB0aGUgZmlsZSBpcyBhY2NlcHRlZFxuICAgIC8vIElmIHlvdSBjYWxsIGl0IHdpdGggYW4gZXJyb3IgbWVzc2FnZSwgdGhlIGZpbGUgaXMgcmVqZWN0ZWRcbiAgICAvLyAoVGhpcyBhbGxvd3MgZm9yIGFzeW5jaHJvbm91cyB2YWxpZGF0aW9uKVxuICAgIC8vXG4gICAgLy8gVGhpcyBmdW5jdGlvbiBjaGVja3MgdGhlIGZpbGVzaXplLCBhbmQgaWYgdGhlIGZpbGUudHlwZSBwYXNzZXMgdGhlXG4gICAgLy8gYGFjY2VwdGVkRmlsZXNgIGNoZWNrLlxuICAgIGFjY2VwdChmaWxlLCBkb25lKSB7XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMubWF4RmlsZXNpemUgJiYgZmlsZS5zaXplID4gdGhpcy5vcHRpb25zLm1heEZpbGVzaXplICogMTA0ODU3NikgZG9uZSh0aGlzLm9wdGlvbnMuZGljdEZpbGVUb29CaWcucmVwbGFjZShcInt7ZmlsZXNpemV9fVwiLCBNYXRoLnJvdW5kKGZpbGUuc2l6ZSAvIDEwMjQgLyAxMC4yNCkgLyAxMDApLnJlcGxhY2UoXCJ7e21heEZpbGVzaXplfX1cIiwgdGhpcy5vcHRpb25zLm1heEZpbGVzaXplKSk7XG4gICAgICAgIGVsc2UgaWYgKCEkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmlzVmFsaWRGaWxlKGZpbGUsIHRoaXMub3B0aW9ucy5hY2NlcHRlZEZpbGVzKSkgZG9uZSh0aGlzLm9wdGlvbnMuZGljdEludmFsaWRGaWxlVHlwZSk7XG4gICAgICAgIGVsc2UgaWYgKHRoaXMub3B0aW9ucy5tYXhGaWxlcyAhPSBudWxsICYmIHRoaXMuZ2V0QWNjZXB0ZWRGaWxlcygpLmxlbmd0aCA+PSB0aGlzLm9wdGlvbnMubWF4RmlsZXMpIHtcbiAgICAgICAgICAgIGRvbmUodGhpcy5vcHRpb25zLmRpY3RNYXhGaWxlc0V4Y2VlZGVkLnJlcGxhY2UoXCJ7e21heEZpbGVzfX1cIiwgdGhpcy5vcHRpb25zLm1heEZpbGVzKSk7XG4gICAgICAgICAgICB0aGlzLmVtaXQoXCJtYXhmaWxlc2V4Y2VlZGVkXCIsIGZpbGUpO1xuICAgICAgICB9IGVsc2UgdGhpcy5vcHRpb25zLmFjY2VwdC5jYWxsKHRoaXMsIGZpbGUsIGRvbmUpO1xuICAgIH1cbiAgICBhZGRGaWxlKGZpbGUpIHtcbiAgICAgICAgZmlsZS51cGxvYWQgPSB7XG4gICAgICAgICAgICB1dWlkOiAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LnV1aWR2NCgpLFxuICAgICAgICAgICAgcHJvZ3Jlc3M6IDAsXG4gICAgICAgICAgICAvLyBTZXR0aW5nIHRoZSB0b3RhbCB1cGxvYWQgc2l6ZSB0byBmaWxlLnNpemUgZm9yIHRoZSBiZWdpbm5pbmdcbiAgICAgICAgICAgIC8vIEl0J3MgYWN0dWFsIGRpZmZlcmVudCB0aGFuIHRoZSBzaXplIHRvIGJlIHRyYW5zbWl0dGVkLlxuICAgICAgICAgICAgdG90YWw6IGZpbGUuc2l6ZSxcbiAgICAgICAgICAgIGJ5dGVzU2VudDogMCxcbiAgICAgICAgICAgIGZpbGVuYW1lOiB0aGlzLl9yZW5hbWVGaWxlKGZpbGUpXG4gICAgICAgIH07XG4gICAgICAgIHRoaXMuZmlsZXMucHVzaChmaWxlKTtcbiAgICAgICAgZmlsZS5zdGF0dXMgPSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkFEREVEO1xuICAgICAgICB0aGlzLmVtaXQoXCJhZGRlZGZpbGVcIiwgZmlsZSk7XG4gICAgICAgIHRoaXMuX2VucXVldWVUaHVtYm5haWwoZmlsZSk7XG4gICAgICAgIHRoaXMuYWNjZXB0KGZpbGUsIChlcnJvcik9PntcbiAgICAgICAgICAgIGlmIChlcnJvcikge1xuICAgICAgICAgICAgICAgIGZpbGUuYWNjZXB0ZWQgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB0aGlzLl9lcnJvclByb2Nlc3NpbmcoW1xuICAgICAgICAgICAgICAgICAgICBmaWxlXG4gICAgICAgICAgICAgICAgXSwgZXJyb3IpOyAvLyBXaWxsIHNldCB0aGUgZmlsZS5zdGF0dXNcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgZmlsZS5hY2NlcHRlZCA9IHRydWU7XG4gICAgICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5hdXRvUXVldWUpIHRoaXMuZW5xdWV1ZUZpbGUoZmlsZSk7XG4gICAgICAgICAgICAgICAgIC8vIFdpbGwgc2V0IC5hY2NlcHRlZCA9IHRydWVcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuX3VwZGF0ZU1heEZpbGVzUmVhY2hlZENsYXNzKCk7XG4gICAgICAgIH0pO1xuICAgIH1cbiAgICAvLyBXcmFwcGVyIGZvciBlbnF1ZXVlRmlsZVxuICAgIGVucXVldWVGaWxlcyhmaWxlcykge1xuICAgICAgICBmb3IgKGxldCBmaWxlIG9mIGZpbGVzKXRoaXMuZW5xdWV1ZUZpbGUoZmlsZSk7XG4gICAgICAgIHJldHVybiBudWxsO1xuICAgIH1cbiAgICBlbnF1ZXVlRmlsZShmaWxlKSB7XG4gICAgICAgIGlmIChmaWxlLnN0YXR1cyA9PT0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5BRERFRCAmJiBmaWxlLmFjY2VwdGVkID09PSB0cnVlKSB7XG4gICAgICAgICAgICBmaWxlLnN0YXR1cyA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuUVVFVUVEO1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5hdXRvUHJvY2Vzc1F1ZXVlKSByZXR1cm4gc2V0VGltZW91dCgoKT0+dGhpcy5wcm9jZXNzUXVldWUoKVxuICAgICAgICAgICAgLCAwKTsgLy8gRGVmZXJyaW5nIHRoZSBjYWxsXG4gICAgICAgIH0gZWxzZSB0aHJvdyBuZXcgRXJyb3IoXCJUaGlzIGZpbGUgY2FuJ3QgYmUgcXVldWVkIGJlY2F1c2UgaXQgaGFzIGFscmVhZHkgYmVlbiBwcm9jZXNzZWQgb3Igd2FzIHJlamVjdGVkLlwiKTtcbiAgICB9XG4gICAgX2VucXVldWVUaHVtYm5haWwoZmlsZSkge1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLmNyZWF0ZUltYWdlVGh1bWJuYWlscyAmJiBmaWxlLnR5cGUubWF0Y2goL2ltYWdlLiovKSAmJiBmaWxlLnNpemUgPD0gdGhpcy5vcHRpb25zLm1heFRodW1ibmFpbEZpbGVzaXplICogMTA0ODU3Nikge1xuICAgICAgICAgICAgdGhpcy5fdGh1bWJuYWlsUXVldWUucHVzaChmaWxlKTtcbiAgICAgICAgICAgIHJldHVybiBzZXRUaW1lb3V0KCgpPT50aGlzLl9wcm9jZXNzVGh1bWJuYWlsUXVldWUoKVxuICAgICAgICAgICAgLCAwKTsgLy8gRGVmZXJyaW5nIHRoZSBjYWxsXG4gICAgICAgIH1cbiAgICB9XG4gICAgX3Byb2Nlc3NUaHVtYm5haWxRdWV1ZSgpIHtcbiAgICAgICAgaWYgKHRoaXMuX3Byb2Nlc3NpbmdUaHVtYm5haWwgfHwgdGhpcy5fdGh1bWJuYWlsUXVldWUubGVuZ3RoID09PSAwKSByZXR1cm47XG4gICAgICAgIHRoaXMuX3Byb2Nlc3NpbmdUaHVtYm5haWwgPSB0cnVlO1xuICAgICAgICBsZXQgZmlsZSA9IHRoaXMuX3RodW1ibmFpbFF1ZXVlLnNoaWZ0KCk7XG4gICAgICAgIHJldHVybiB0aGlzLmNyZWF0ZVRodW1ibmFpbChmaWxlLCB0aGlzLm9wdGlvbnMudGh1bWJuYWlsV2lkdGgsIHRoaXMub3B0aW9ucy50aHVtYm5haWxIZWlnaHQsIHRoaXMub3B0aW9ucy50aHVtYm5haWxNZXRob2QsIHRydWUsIChkYXRhVXJsKT0+e1xuICAgICAgICAgICAgdGhpcy5lbWl0KFwidGh1bWJuYWlsXCIsIGZpbGUsIGRhdGFVcmwpO1xuICAgICAgICAgICAgdGhpcy5fcHJvY2Vzc2luZ1RodW1ibmFpbCA9IGZhbHNlO1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuX3Byb2Nlc3NUaHVtYm5haWxRdWV1ZSgpO1xuICAgICAgICB9KTtcbiAgICB9XG4gICAgLy8gQ2FuIGJlIGNhbGxlZCBieSB0aGUgdXNlciB0byByZW1vdmUgYSBmaWxlXG4gICAgcmVtb3ZlRmlsZShmaWxlKSB7XG4gICAgICAgIGlmIChmaWxlLnN0YXR1cyA9PT0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5VUExPQURJTkcpIHRoaXMuY2FuY2VsVXBsb2FkKGZpbGUpO1xuICAgICAgICB0aGlzLmZpbGVzID0gJDNlZDI2OWYyZjBmYjIyNGIkdmFyJHdpdGhvdXQodGhpcy5maWxlcywgZmlsZSk7XG4gICAgICAgIHRoaXMuZW1pdChcInJlbW92ZWRmaWxlXCIsIGZpbGUpO1xuICAgICAgICBpZiAodGhpcy5maWxlcy5sZW5ndGggPT09IDApIHJldHVybiB0aGlzLmVtaXQoXCJyZXNldFwiKTtcbiAgICB9XG4gICAgLy8gUmVtb3ZlcyBhbGwgZmlsZXMgdGhhdCBhcmVuJ3QgY3VycmVudGx5IHByb2Nlc3NlZCBmcm9tIHRoZSBsaXN0XG4gICAgcmVtb3ZlQWxsRmlsZXMoY2FuY2VsSWZOZWNlc3NhcnkpIHtcbiAgICAgICAgLy8gQ3JlYXRlIGEgY29weSBvZiBmaWxlcyBzaW5jZSByZW1vdmVGaWxlKCkgY2hhbmdlcyB0aGUgQGZpbGVzIGFycmF5LlxuICAgICAgICBpZiAoY2FuY2VsSWZOZWNlc3NhcnkgPT0gbnVsbCkgY2FuY2VsSWZOZWNlc3NhcnkgPSBmYWxzZTtcbiAgICAgICAgZm9yIChsZXQgZmlsZSBvZiB0aGlzLmZpbGVzLnNsaWNlKCkpaWYgKGZpbGUuc3RhdHVzICE9PSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LlVQTE9BRElORyB8fCBjYW5jZWxJZk5lY2Vzc2FyeSkgdGhpcy5yZW1vdmVGaWxlKGZpbGUpO1xuICAgICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG4gICAgLy8gUmVzaXplcyBhbiBpbWFnZSBiZWZvcmUgaXQgZ2V0cyBzZW50IHRvIHRoZSBzZXJ2ZXIuIFRoaXMgZnVuY3Rpb24gaXMgdGhlIGRlZmF1bHQgYmVoYXZpb3Igb2ZcbiAgICAvLyBgb3B0aW9ucy50cmFuc2Zvcm1GaWxlYCBpZiBgcmVzaXplV2lkdGhgIG9yIGByZXNpemVIZWlnaHRgIGFyZSBzZXQuIFRoZSBjYWxsYmFjayBpcyBpbnZva2VkIHdpdGhcbiAgICAvLyB0aGUgcmVzaXplZCBibG9iLlxuICAgIHJlc2l6ZUltYWdlKGZpbGUsIHdpZHRoLCBoZWlnaHQsIHJlc2l6ZU1ldGhvZCwgY2FsbGJhY2spIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuY3JlYXRlVGh1bWJuYWlsKGZpbGUsIHdpZHRoLCBoZWlnaHQsIHJlc2l6ZU1ldGhvZCwgdHJ1ZSwgKGRhdGFVcmwsIGNhbnZhcyk9PntcbiAgICAgICAgICAgIGlmIChjYW52YXMgPT0gbnVsbCkgLy8gVGhlIGltYWdlIGhhcyBub3QgYmVlbiByZXNpemVkXG4gICAgICAgICAgICByZXR1cm4gY2FsbGJhY2soZmlsZSk7XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBsZXQgeyByZXNpemVNaW1lVHlwZTogcmVzaXplTWltZVR5cGUgIH0gPSB0aGlzLm9wdGlvbnM7XG4gICAgICAgICAgICAgICAgaWYgKHJlc2l6ZU1pbWVUeXBlID09IG51bGwpIHJlc2l6ZU1pbWVUeXBlID0gZmlsZS50eXBlO1xuICAgICAgICAgICAgICAgIGxldCByZXNpemVkRGF0YVVSTCA9IGNhbnZhcy50b0RhdGFVUkwocmVzaXplTWltZVR5cGUsIHRoaXMub3B0aW9ucy5yZXNpemVRdWFsaXR5KTtcbiAgICAgICAgICAgICAgICBpZiAocmVzaXplTWltZVR5cGUgPT09IFwiaW1hZ2UvanBlZ1wiIHx8IHJlc2l6ZU1pbWVUeXBlID09PSBcImltYWdlL2pwZ1wiKSAvLyBOb3cgYWRkIHRoZSBvcmlnaW5hbCBFWElGIGluZm9ybWF0aW9uXG4gICAgICAgICAgICAgICAgcmVzaXplZERhdGFVUkwgPSAkM2VkMjY5ZjJmMGZiMjI0YiR2YXIkRXhpZlJlc3RvcmUucmVzdG9yZShmaWxlLmRhdGFVUkwsIHJlc2l6ZWREYXRhVVJMKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gY2FsbGJhY2soJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5kYXRhVVJJdG9CbG9iKHJlc2l6ZWREYXRhVVJMKSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cbiAgICBjcmVhdGVUaHVtYm5haWwoZmlsZSwgd2lkdGgsIGhlaWdodCwgcmVzaXplTWV0aG9kLCBmaXhPcmllbnRhdGlvbiwgY2FsbGJhY2spIHtcbiAgICAgICAgbGV0IGZpbGVSZWFkZXIgPSBuZXcgRmlsZVJlYWRlcigpO1xuICAgICAgICBmaWxlUmVhZGVyLm9ubG9hZCA9ICgpPT57XG4gICAgICAgICAgICBmaWxlLmRhdGFVUkwgPSBmaWxlUmVhZGVyLnJlc3VsdDtcbiAgICAgICAgICAgIC8vIERvbid0IGJvdGhlciBjcmVhdGluZyBhIHRodW1ibmFpbCBmb3IgU1ZHIGltYWdlcyBzaW5jZSB0aGV5J3JlIHZlY3RvclxuICAgICAgICAgICAgaWYgKGZpbGUudHlwZSA9PT0gXCJpbWFnZS9zdmcreG1sXCIpIHtcbiAgICAgICAgICAgICAgICBpZiAoY2FsbGJhY2sgIT0gbnVsbCkgY2FsbGJhY2soZmlsZVJlYWRlci5yZXN1bHQpO1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHRoaXMuY3JlYXRlVGh1bWJuYWlsRnJvbVVybChmaWxlLCB3aWR0aCwgaGVpZ2h0LCByZXNpemVNZXRob2QsIGZpeE9yaWVudGF0aW9uLCBjYWxsYmFjayk7XG4gICAgICAgIH07XG4gICAgICAgIGZpbGVSZWFkZXIucmVhZEFzRGF0YVVSTChmaWxlKTtcbiAgICB9XG4gICAgLy8gYG1vY2tGaWxlYCBuZWVkcyB0byBoYXZlIHRoZXNlIGF0dHJpYnV0ZXM6XG4gICAgLy9cbiAgICAvLyAgICAgeyBuYW1lOiAnbmFtZScsIHNpemU6IDEyMzQ1LCBpbWFnZVVybDogJycgfVxuICAgIC8vXG4gICAgLy8gYGNhbGxiYWNrYCB3aWxsIGJlIGludm9rZWQgd2hlbiB0aGUgaW1hZ2UgaGFzIGJlZW4gZG93bmxvYWRlZCBhbmQgZGlzcGxheWVkLlxuICAgIC8vIGBjcm9zc09yaWdpbmAgd2lsbCBiZSBhZGRlZCB0byB0aGUgYGltZ2AgdGFnIHdoZW4gYWNjZXNzaW5nIHRoZSBmaWxlLlxuICAgIGRpc3BsYXlFeGlzdGluZ0ZpbGUobW9ja0ZpbGUsIGltYWdlVXJsLCBjYWxsYmFjaywgY3Jvc3NPcmlnaW4sIHJlc2l6ZVRodW1ibmFpbCA9IHRydWUpIHtcbiAgICAgICAgdGhpcy5lbWl0KFwiYWRkZWRmaWxlXCIsIG1vY2tGaWxlKTtcbiAgICAgICAgdGhpcy5lbWl0KFwiY29tcGxldGVcIiwgbW9ja0ZpbGUpO1xuICAgICAgICBpZiAoIXJlc2l6ZVRodW1ibmFpbCkge1xuICAgICAgICAgICAgdGhpcy5lbWl0KFwidGh1bWJuYWlsXCIsIG1vY2tGaWxlLCBpbWFnZVVybCk7XG4gICAgICAgICAgICBpZiAoY2FsbGJhY2spIGNhbGxiYWNrKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBsZXQgb25Eb25lID0gKHRodW1ibmFpbCk9PntcbiAgICAgICAgICAgICAgICB0aGlzLmVtaXQoXCJ0aHVtYm5haWxcIiwgbW9ja0ZpbGUsIHRodW1ibmFpbCk7XG4gICAgICAgICAgICAgICAgaWYgKGNhbGxiYWNrKSBjYWxsYmFjaygpO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIG1vY2tGaWxlLmRhdGFVUkwgPSBpbWFnZVVybDtcbiAgICAgICAgICAgIHRoaXMuY3JlYXRlVGh1bWJuYWlsRnJvbVVybChtb2NrRmlsZSwgdGhpcy5vcHRpb25zLnRodW1ibmFpbFdpZHRoLCB0aGlzLm9wdGlvbnMudGh1bWJuYWlsSGVpZ2h0LCB0aGlzLm9wdGlvbnMudGh1bWJuYWlsTWV0aG9kLCB0aGlzLm9wdGlvbnMuZml4T3JpZW50YXRpb24sIG9uRG9uZSwgY3Jvc3NPcmlnaW4pO1xuICAgICAgICB9XG4gICAgfVxuICAgIGNyZWF0ZVRodW1ibmFpbEZyb21VcmwoZmlsZSwgd2lkdGgsIGhlaWdodCwgcmVzaXplTWV0aG9kLCBmaXhPcmllbnRhdGlvbiwgY2FsbGJhY2ssIGNyb3NzT3JpZ2luKSB7XG4gICAgICAgIC8vIE5vdCB1c2luZyBgbmV3IEltYWdlYCBoZXJlIGJlY2F1c2Ugb2YgYSBidWcgaW4gbGF0ZXN0IENocm9tZSB2ZXJzaW9ucy5cbiAgICAgICAgLy8gU2VlIGh0dHBzOi8vZ2l0aHViLmNvbS9lbnlvL2Ryb3B6b25lL3B1bGwvMjI2XG4gICAgICAgIGxldCBpbWcgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiaW1nXCIpO1xuICAgICAgICBpZiAoY3Jvc3NPcmlnaW4pIGltZy5jcm9zc09yaWdpbiA9IGNyb3NzT3JpZ2luO1xuICAgICAgICAvLyBmaXhPcmllbnRhdGlvbiBpcyBub3QgbmVlZGVkIGFueW1vcmUgd2l0aCBicm93c2VycyBoYW5kbGluZyBpbWFnZU9yaWVudGF0aW9uXG4gICAgICAgIGZpeE9yaWVudGF0aW9uID0gZ2V0Q29tcHV0ZWRTdHlsZShkb2N1bWVudC5ib2R5KVtcImltYWdlT3JpZW50YXRpb25cIl0gPT0gXCJmcm9tLWltYWdlXCIgPyBmYWxzZSA6IGZpeE9yaWVudGF0aW9uO1xuICAgICAgICBpbWcub25sb2FkID0gKCk9PntcbiAgICAgICAgICAgIGxldCBsb2FkRXhpZiA9IChjYWxsYmFjayk9PmNhbGxiYWNrKDEpXG4gICAgICAgICAgICA7XG4gICAgICAgICAgICBpZiAodHlwZW9mIEVYSUYgIT09IFwidW5kZWZpbmVkXCIgJiYgRVhJRiAhPT0gbnVsbCAmJiBmaXhPcmllbnRhdGlvbikgbG9hZEV4aWYgPSAoY2FsbGJhY2spPT5FWElGLmdldERhdGEoaW1nLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGNhbGxiYWNrKEVYSUYuZ2V0VGFnKHRoaXMsIFwiT3JpZW50YXRpb25cIikpO1xuICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICA7XG4gICAgICAgICAgICByZXR1cm4gbG9hZEV4aWYoKG9yaWVudGF0aW9uKT0+e1xuICAgICAgICAgICAgICAgIGZpbGUud2lkdGggPSBpbWcud2lkdGg7XG4gICAgICAgICAgICAgICAgZmlsZS5oZWlnaHQgPSBpbWcuaGVpZ2h0O1xuICAgICAgICAgICAgICAgIGxldCByZXNpemVJbmZvID0gdGhpcy5vcHRpb25zLnJlc2l6ZS5jYWxsKHRoaXMsIGZpbGUsIHdpZHRoLCBoZWlnaHQsIHJlc2l6ZU1ldGhvZCk7XG4gICAgICAgICAgICAgICAgbGV0IGNhbnZhcyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJjYW52YXNcIik7XG4gICAgICAgICAgICAgICAgbGV0IGN0eCA9IGNhbnZhcy5nZXRDb250ZXh0KFwiMmRcIik7XG4gICAgICAgICAgICAgICAgY2FudmFzLndpZHRoID0gcmVzaXplSW5mby50cmdXaWR0aDtcbiAgICAgICAgICAgICAgICBjYW52YXMuaGVpZ2h0ID0gcmVzaXplSW5mby50cmdIZWlnaHQ7XG4gICAgICAgICAgICAgICAgaWYgKG9yaWVudGF0aW9uID4gNCkge1xuICAgICAgICAgICAgICAgICAgICBjYW52YXMud2lkdGggPSByZXNpemVJbmZvLnRyZ0hlaWdodDtcbiAgICAgICAgICAgICAgICAgICAgY2FudmFzLmhlaWdodCA9IHJlc2l6ZUluZm8udHJnV2lkdGg7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHN3aXRjaChvcmllbnRhdGlvbil7XG4gICAgICAgICAgICAgICAgICAgIGNhc2UgMjpcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIGhvcml6b250YWwgZmxpcFxuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnRyYW5zbGF0ZShjYW52YXMud2lkdGgsIDApO1xuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnNjYWxlKC0xLCAxKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBjYXNlIDM6XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyAxODDCsCByb3RhdGUgbGVmdFxuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnRyYW5zbGF0ZShjYW52YXMud2lkdGgsIGNhbnZhcy5oZWlnaHQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnJvdGF0ZShNYXRoLlBJKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBjYXNlIDQ6XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyB2ZXJ0aWNhbCBmbGlwXG4gICAgICAgICAgICAgICAgICAgICAgICBjdHgudHJhbnNsYXRlKDAsIGNhbnZhcy5oZWlnaHQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnNjYWxlKDEsIC0xKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBjYXNlIDU6XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyB2ZXJ0aWNhbCBmbGlwICsgOTAgcm90YXRlIHJpZ2h0XG4gICAgICAgICAgICAgICAgICAgICAgICBjdHgucm90YXRlKDAuNSAqIE1hdGguUEkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnNjYWxlKDEsIC0xKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICBjYXNlIDY6XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyA5MMKwIHJvdGF0ZSByaWdodFxuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnJvdGF0ZSgwLjUgKiBNYXRoLlBJKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGN0eC50cmFuc2xhdGUoMCwgLWNhbnZhcy53aWR0aCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgY2FzZSA3OlxuICAgICAgICAgICAgICAgICAgICAgICAgLy8gaG9yaXpvbnRhbCBmbGlwICsgOTAgcm90YXRlIHJpZ2h0XG4gICAgICAgICAgICAgICAgICAgICAgICBjdHgucm90YXRlKDAuNSAqIE1hdGguUEkpO1xuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnRyYW5zbGF0ZShjYW52YXMuaGVpZ2h0LCAtY2FudmFzLndpZHRoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGN0eC5zY2FsZSgtMSwgMSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgY2FzZSA4OlxuICAgICAgICAgICAgICAgICAgICAgICAgLy8gOTDCsCByb3RhdGUgbGVmdFxuICAgICAgICAgICAgICAgICAgICAgICAgY3R4LnJvdGF0ZSgtMC41ICogTWF0aC5QSSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBjdHgudHJhbnNsYXRlKC1jYW52YXMuaGVpZ2h0LCAwKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAvLyBUaGlzIGlzIGEgYnVnZml4IGZvciBpT1MnIHNjYWxpbmcgYnVnLlxuICAgICAgICAgICAgICAgICQzZWQyNjlmMmYwZmIyMjRiJHZhciRkcmF3SW1hZ2VJT1NGaXgoY3R4LCBpbWcsIHJlc2l6ZUluZm8uc3JjWCAhPSBudWxsID8gcmVzaXplSW5mby5zcmNYIDogMCwgcmVzaXplSW5mby5zcmNZICE9IG51bGwgPyByZXNpemVJbmZvLnNyY1kgOiAwLCByZXNpemVJbmZvLnNyY1dpZHRoLCByZXNpemVJbmZvLnNyY0hlaWdodCwgcmVzaXplSW5mby50cmdYICE9IG51bGwgPyByZXNpemVJbmZvLnRyZ1ggOiAwLCByZXNpemVJbmZvLnRyZ1kgIT0gbnVsbCA/IHJlc2l6ZUluZm8udHJnWSA6IDAsIHJlc2l6ZUluZm8udHJnV2lkdGgsIHJlc2l6ZUluZm8udHJnSGVpZ2h0KTtcbiAgICAgICAgICAgICAgICBsZXQgdGh1bWJuYWlsID0gY2FudmFzLnRvRGF0YVVSTChcImltYWdlL3BuZ1wiKTtcbiAgICAgICAgICAgICAgICBpZiAoY2FsbGJhY2sgIT0gbnVsbCkgcmV0dXJuIGNhbGxiYWNrKHRodW1ibmFpbCwgY2FudmFzKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9O1xuICAgICAgICBpZiAoY2FsbGJhY2sgIT0gbnVsbCkgaW1nLm9uZXJyb3IgPSBjYWxsYmFjaztcbiAgICAgICAgcmV0dXJuIGltZy5zcmMgPSBmaWxlLmRhdGFVUkw7XG4gICAgfVxuICAgIC8vIEdvZXMgdGhyb3VnaCB0aGUgcXVldWUgYW5kIHByb2Nlc3NlcyBmaWxlcyBpZiB0aGVyZSBhcmVuJ3QgdG9vIG1hbnkgYWxyZWFkeS5cbiAgICBwcm9jZXNzUXVldWUoKSB7XG4gICAgICAgIGxldCB7IHBhcmFsbGVsVXBsb2FkczogcGFyYWxsZWxVcGxvYWRzICB9ID0gdGhpcy5vcHRpb25zO1xuICAgICAgICBsZXQgcHJvY2Vzc2luZ0xlbmd0aCA9IHRoaXMuZ2V0VXBsb2FkaW5nRmlsZXMoKS5sZW5ndGg7XG4gICAgICAgIGxldCBpID0gcHJvY2Vzc2luZ0xlbmd0aDtcbiAgICAgICAgLy8gVGhlcmUgYXJlIGFscmVhZHkgYXQgbGVhc3QgYXMgbWFueSBmaWxlcyB1cGxvYWRpbmcgdGhhbiBzaG91bGQgYmVcbiAgICAgICAgaWYgKHByb2Nlc3NpbmdMZW5ndGggPj0gcGFyYWxsZWxVcGxvYWRzKSByZXR1cm47XG4gICAgICAgIGxldCBxdWV1ZWRGaWxlcyA9IHRoaXMuZ2V0UXVldWVkRmlsZXMoKTtcbiAgICAgICAgaWYgKCEocXVldWVkRmlsZXMubGVuZ3RoID4gMCkpIHJldHVybjtcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy51cGxvYWRNdWx0aXBsZSkgLy8gVGhlIGZpbGVzIHNob3VsZCBiZSB1cGxvYWRlZCBpbiBvbmUgcmVxdWVzdFxuICAgICAgICByZXR1cm4gdGhpcy5wcm9jZXNzRmlsZXMocXVldWVkRmlsZXMuc2xpY2UoMCwgcGFyYWxsZWxVcGxvYWRzIC0gcHJvY2Vzc2luZ0xlbmd0aCkpO1xuICAgICAgICBlbHNlIHdoaWxlKGkgPCBwYXJhbGxlbFVwbG9hZHMpe1xuICAgICAgICAgICAgaWYgKCFxdWV1ZWRGaWxlcy5sZW5ndGgpIHJldHVybjtcbiAgICAgICAgICAgICAvLyBOb3RoaW5nIGxlZnQgdG8gcHJvY2Vzc1xuICAgICAgICAgICAgdGhpcy5wcm9jZXNzRmlsZShxdWV1ZWRGaWxlcy5zaGlmdCgpKTtcbiAgICAgICAgICAgIGkrKztcbiAgICAgICAgfVxuICAgIH1cbiAgICAvLyBXcmFwcGVyIGZvciBgcHJvY2Vzc0ZpbGVzYFxuICAgIHByb2Nlc3NGaWxlKGZpbGUpIHtcbiAgICAgICAgcmV0dXJuIHRoaXMucHJvY2Vzc0ZpbGVzKFtcbiAgICAgICAgICAgIGZpbGVcbiAgICAgICAgXSk7XG4gICAgfVxuICAgIC8vIExvYWRzIHRoZSBmaWxlLCB0aGVuIGNhbGxzIGZpbmlzaGVkTG9hZGluZygpXG4gICAgcHJvY2Vzc0ZpbGVzKGZpbGVzKSB7XG4gICAgICAgIGZvciAobGV0IGZpbGUgb2YgZmlsZXMpe1xuICAgICAgICAgICAgZmlsZS5wcm9jZXNzaW5nID0gdHJ1ZTsgLy8gQmFja3dhcmRzIGNvbXBhdGliaWxpdHlcbiAgICAgICAgICAgIGZpbGUuc3RhdHVzID0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5VUExPQURJTkc7XG4gICAgICAgICAgICB0aGlzLmVtaXQoXCJwcm9jZXNzaW5nXCIsIGZpbGUpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUpIHRoaXMuZW1pdChcInByb2Nlc3NpbmdtdWx0aXBsZVwiLCBmaWxlcyk7XG4gICAgICAgIHJldHVybiB0aGlzLnVwbG9hZEZpbGVzKGZpbGVzKTtcbiAgICB9XG4gICAgX2dldEZpbGVzV2l0aFhocih4aHIpIHtcbiAgICAgICAgbGV0IGZpbGVzO1xuICAgICAgICByZXR1cm4gZmlsZXMgPSB0aGlzLmZpbGVzLmZpbHRlcigoZmlsZSk9PmZpbGUueGhyID09PSB4aHJcbiAgICAgICAgKS5tYXAoKGZpbGUpPT5maWxlXG4gICAgICAgICk7XG4gICAgfVxuICAgIC8vIENhbmNlbHMgdGhlIGZpbGUgdXBsb2FkIGFuZCBzZXRzIHRoZSBzdGF0dXMgdG8gQ0FOQ0VMRURcbiAgICAvLyAqKmlmKiogdGhlIGZpbGUgaXMgYWN0dWFsbHkgYmVpbmcgdXBsb2FkZWQuXG4gICAgLy8gSWYgaXQncyBzdGlsbCBpbiB0aGUgcXVldWUsIHRoZSBmaWxlIGlzIGJlaW5nIHJlbW92ZWQgZnJvbSBpdCBhbmQgdGhlIHN0YXR1c1xuICAgIC8vIHNldCB0byBDQU5DRUxFRC5cbiAgICBjYW5jZWxVcGxvYWQoZmlsZSkge1xuICAgICAgICBpZiAoZmlsZS5zdGF0dXMgPT09ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuVVBMT0FESU5HKSB7XG4gICAgICAgICAgICBsZXQgZ3JvdXBlZEZpbGVzID0gdGhpcy5fZ2V0RmlsZXNXaXRoWGhyKGZpbGUueGhyKTtcbiAgICAgICAgICAgIGZvciAobGV0IGdyb3VwZWRGaWxlIG9mIGdyb3VwZWRGaWxlcylncm91cGVkRmlsZS5zdGF0dXMgPSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkNBTkNFTEVEO1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBmaWxlLnhociAhPT0gXCJ1bmRlZmluZWRcIikgZmlsZS54aHIuYWJvcnQoKTtcbiAgICAgICAgICAgIGZvciAobGV0IGdyb3VwZWRGaWxlMSBvZiBncm91cGVkRmlsZXMpdGhpcy5lbWl0KFwiY2FuY2VsZWRcIiwgZ3JvdXBlZEZpbGUxKTtcbiAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUpIHRoaXMuZW1pdChcImNhbmNlbGVkbXVsdGlwbGVcIiwgZ3JvdXBlZEZpbGVzKTtcbiAgICAgICAgfSBlbHNlIGlmIChmaWxlLnN0YXR1cyA9PT0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5BRERFRCB8fCBmaWxlLnN0YXR1cyA9PT0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5RVUVVRUQpIHtcbiAgICAgICAgICAgIGZpbGUuc3RhdHVzID0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5DQU5DRUxFRDtcbiAgICAgICAgICAgIHRoaXMuZW1pdChcImNhbmNlbGVkXCIsIGZpbGUpO1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy51cGxvYWRNdWx0aXBsZSkgdGhpcy5lbWl0KFwiY2FuY2VsZWRtdWx0aXBsZVwiLCBbXG4gICAgICAgICAgICAgICAgZmlsZVxuICAgICAgICAgICAgXSk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5hdXRvUHJvY2Vzc1F1ZXVlKSByZXR1cm4gdGhpcy5wcm9jZXNzUXVldWUoKTtcbiAgICB9XG4gICAgcmVzb2x2ZU9wdGlvbihvcHRpb24sIC4uLmFyZ3MpIHtcbiAgICAgICAgaWYgKHR5cGVvZiBvcHRpb24gPT09IFwiZnVuY3Rpb25cIikgcmV0dXJuIG9wdGlvbi5hcHBseSh0aGlzLCBhcmdzKTtcbiAgICAgICAgcmV0dXJuIG9wdGlvbjtcbiAgICB9XG4gICAgdXBsb2FkRmlsZShmaWxlKSB7XG4gICAgICAgIHJldHVybiB0aGlzLnVwbG9hZEZpbGVzKFtcbiAgICAgICAgICAgIGZpbGVcbiAgICAgICAgXSk7XG4gICAgfVxuICAgIHVwbG9hZEZpbGVzKGZpbGVzKSB7XG4gICAgICAgIHRoaXMuX3RyYW5zZm9ybUZpbGVzKGZpbGVzLCAodHJhbnNmb3JtZWRGaWxlcyk9PntcbiAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMuY2h1bmtpbmcpIHtcbiAgICAgICAgICAgICAgICAvLyBDaHVua2luZyBpcyBub3QgYWxsb3dlZCB0byBiZSB1c2VkIHdpdGggYHVwbG9hZE11bHRpcGxlYCBzbyB3ZSBrbm93XG4gICAgICAgICAgICAgICAgLy8gdGhhdCB0aGVyZSBpcyBvbmx5IF9fb25lX19maWxlLlxuICAgICAgICAgICAgICAgIGxldCB0cmFuc2Zvcm1lZEZpbGUgPSB0cmFuc2Zvcm1lZEZpbGVzWzBdO1xuICAgICAgICAgICAgICAgIGZpbGVzWzBdLnVwbG9hZC5jaHVua2VkID0gdGhpcy5vcHRpb25zLmNodW5raW5nICYmICh0aGlzLm9wdGlvbnMuZm9yY2VDaHVua2luZyB8fCB0cmFuc2Zvcm1lZEZpbGUuc2l6ZSA+IHRoaXMub3B0aW9ucy5jaHVua1NpemUpO1xuICAgICAgICAgICAgICAgIGZpbGVzWzBdLnVwbG9hZC50b3RhbENodW5rQ291bnQgPSBNYXRoLmNlaWwodHJhbnNmb3JtZWRGaWxlLnNpemUgLyB0aGlzLm9wdGlvbnMuY2h1bmtTaXplKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmIChmaWxlc1swXS51cGxvYWQuY2h1bmtlZCkge1xuICAgICAgICAgICAgICAgIC8vIFRoaXMgZmlsZSBzaG91bGQgYmUgc2VudCBpbiBjaHVua3MhXG4gICAgICAgICAgICAgICAgLy8gSWYgdGhlIGNodW5raW5nIG9wdGlvbiBpcyBzZXQsIHdlICoqa25vdyoqIHRoYXQgdGhlcmUgY2FuIG9ubHkgYmUgKipvbmUqKiBmaWxlLCBzaW5jZVxuICAgICAgICAgICAgICAgIC8vIHVwbG9hZE11bHRpcGxlIGlzIG5vdCBhbGxvd2VkIHdpdGggdGhpcyBvcHRpb24uXG4gICAgICAgICAgICAgICAgbGV0IGZpbGUgPSBmaWxlc1swXTtcbiAgICAgICAgICAgICAgICBsZXQgdHJhbnNmb3JtZWRGaWxlID0gdHJhbnNmb3JtZWRGaWxlc1swXTtcbiAgICAgICAgICAgICAgICBsZXQgc3RhcnRlZENodW5rQ291bnQgPSAwO1xuICAgICAgICAgICAgICAgIGZpbGUudXBsb2FkLmNodW5rcyA9IFtdO1xuICAgICAgICAgICAgICAgIGxldCBoYW5kbGVOZXh0Q2h1bmsgPSAoKT0+e1xuICAgICAgICAgICAgICAgICAgICBsZXQgY2h1bmtJbmRleCA9IDA7XG4gICAgICAgICAgICAgICAgICAgIC8vIEZpbmQgdGhlIG5leHQgaXRlbSBpbiBmaWxlLnVwbG9hZC5jaHVua3MgdGhhdCBpcyBub3QgZGVmaW5lZCB5ZXQuXG4gICAgICAgICAgICAgICAgICAgIHdoaWxlKGZpbGUudXBsb2FkLmNodW5rc1tjaHVua0luZGV4XSAhPT0gdW5kZWZpbmVkKWNodW5rSW5kZXgrKztcbiAgICAgICAgICAgICAgICAgICAgLy8gVGhpcyBtZWFucywgdGhhdCBhbGwgY2h1bmtzIGhhdmUgYWxyZWFkeSBiZWVuIHN0YXJ0ZWQuXG4gICAgICAgICAgICAgICAgICAgIGlmIChjaHVua0luZGV4ID49IGZpbGUudXBsb2FkLnRvdGFsQ2h1bmtDb3VudCkgcmV0dXJuO1xuICAgICAgICAgICAgICAgICAgICBzdGFydGVkQ2h1bmtDb3VudCsrO1xuICAgICAgICAgICAgICAgICAgICBsZXQgc3RhcnQgPSBjaHVua0luZGV4ICogdGhpcy5vcHRpb25zLmNodW5rU2l6ZTtcbiAgICAgICAgICAgICAgICAgICAgbGV0IGVuZCA9IE1hdGgubWluKHN0YXJ0ICsgdGhpcy5vcHRpb25zLmNodW5rU2l6ZSwgdHJhbnNmb3JtZWRGaWxlLnNpemUpO1xuICAgICAgICAgICAgICAgICAgICBsZXQgZGF0YUJsb2NrID0ge1xuICAgICAgICAgICAgICAgICAgICAgICAgbmFtZTogdGhpcy5fZ2V0UGFyYW1OYW1lKDApLFxuICAgICAgICAgICAgICAgICAgICAgICAgZGF0YTogdHJhbnNmb3JtZWRGaWxlLndlYmtpdFNsaWNlID8gdHJhbnNmb3JtZWRGaWxlLndlYmtpdFNsaWNlKHN0YXJ0LCBlbmQpIDogdHJhbnNmb3JtZWRGaWxlLnNsaWNlKHN0YXJ0LCBlbmQpLFxuICAgICAgICAgICAgICAgICAgICAgICAgZmlsZW5hbWU6IGZpbGUudXBsb2FkLmZpbGVuYW1lLFxuICAgICAgICAgICAgICAgICAgICAgICAgY2h1bmtJbmRleDogY2h1bmtJbmRleFxuICAgICAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgICAgICBmaWxlLnVwbG9hZC5jaHVua3NbY2h1bmtJbmRleF0gPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBmaWxlOiBmaWxlLFxuICAgICAgICAgICAgICAgICAgICAgICAgaW5kZXg6IGNodW5rSW5kZXgsXG4gICAgICAgICAgICAgICAgICAgICAgICBkYXRhQmxvY2s6IGRhdGFCbG9jayxcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0YXR1czogJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5VUExPQURJTkcsXG4gICAgICAgICAgICAgICAgICAgICAgICBwcm9ncmVzczogMCxcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHJpZXM6IDBcbiAgICAgICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5fdXBsb2FkRGF0YShmaWxlcywgW1xuICAgICAgICAgICAgICAgICAgICAgICAgZGF0YUJsb2NrXG4gICAgICAgICAgICAgICAgICAgIF0pO1xuICAgICAgICAgICAgICAgIH07XG4gICAgICAgICAgICAgICAgZmlsZS51cGxvYWQuZmluaXNoZWRDaHVua1VwbG9hZCA9IChjaHVuaywgcmVzcG9uc2UpPT57XG4gICAgICAgICAgICAgICAgICAgIGxldCBhbGxGaW5pc2hlZCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIGNodW5rLnN0YXR1cyA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuU1VDQ0VTUztcbiAgICAgICAgICAgICAgICAgICAgLy8gQ2xlYXIgdGhlIGRhdGEgZnJvbSB0aGUgY2h1bmtcbiAgICAgICAgICAgICAgICAgICAgY2h1bmsuZGF0YUJsb2NrID0gbnVsbDtcbiAgICAgICAgICAgICAgICAgICAgY2h1bmsucmVzcG9uc2UgPSBjaHVuay54aHIucmVzcG9uc2VUZXh0O1xuICAgICAgICAgICAgICAgICAgICBjaHVuay5yZXNwb25zZUhlYWRlcnMgPSBjaHVuay54aHIuZ2V0QWxsUmVzcG9uc2VIZWFkZXJzKCk7XG4gICAgICAgICAgICAgICAgICAgIC8vIExlYXZpbmcgdGhpcyByZWZlcmVuY2UgdG8geGhyIHdpbGwgY2F1c2UgbWVtb3J5IGxlYWtzLlxuICAgICAgICAgICAgICAgICAgICBjaHVuay54aHIgPSBudWxsO1xuICAgICAgICAgICAgICAgICAgICBmb3IobGV0IGkgPSAwOyBpIDwgZmlsZS51cGxvYWQudG90YWxDaHVua0NvdW50OyBpKyspe1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGZpbGUudXBsb2FkLmNodW5rc1tpXSA9PT0gdW5kZWZpbmVkKSByZXR1cm4gaGFuZGxlTmV4dENodW5rKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoZmlsZS51cGxvYWQuY2h1bmtzW2ldLnN0YXR1cyAhPT0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5TVUNDRVNTKSBhbGxGaW5pc2hlZCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIGlmIChhbGxGaW5pc2hlZCkgdGhpcy5vcHRpb25zLmNodW5rc1VwbG9hZGVkKGZpbGUsICgpPT57XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLl9maW5pc2hlZChmaWxlcywgcmVzcG9uc2UsIG51bGwpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMucGFyYWxsZWxDaHVua1VwbG9hZHMpIGZvcihsZXQgaSA9IDA7IGkgPCBmaWxlLnVwbG9hZC50b3RhbENodW5rQ291bnQ7IGkrKyloYW5kbGVOZXh0Q2h1bmsoKTtcbiAgICAgICAgICAgICAgICBlbHNlIGhhbmRsZU5leHRDaHVuaygpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBsZXQgZGF0YUJsb2NrcyA9IFtdO1xuICAgICAgICAgICAgICAgIGZvcihsZXQgaSA9IDA7IGkgPCBmaWxlcy5sZW5ndGg7IGkrKylkYXRhQmxvY2tzW2ldID0ge1xuICAgICAgICAgICAgICAgICAgICBuYW1lOiB0aGlzLl9nZXRQYXJhbU5hbWUoaSksXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6IHRyYW5zZm9ybWVkRmlsZXNbaV0sXG4gICAgICAgICAgICAgICAgICAgIGZpbGVuYW1lOiBmaWxlc1tpXS51cGxvYWQuZmlsZW5hbWVcbiAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIHRoaXMuX3VwbG9hZERhdGEoZmlsZXMsIGRhdGFCbG9ja3MpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9XG4gICAgLy8vIFJldHVybnMgdGhlIHJpZ2h0IGNodW5rIGZvciBnaXZlbiBmaWxlIGFuZCB4aHJcbiAgICBfZ2V0Q2h1bmsoZmlsZSwgeGhyKSB7XG4gICAgICAgIGZvcihsZXQgaSA9IDA7IGkgPCBmaWxlLnVwbG9hZC50b3RhbENodW5rQ291bnQ7IGkrKyl7XG4gICAgICAgICAgICBpZiAoZmlsZS51cGxvYWQuY2h1bmtzW2ldICE9PSB1bmRlZmluZWQgJiYgZmlsZS51cGxvYWQuY2h1bmtzW2ldLnhociA9PT0geGhyKSByZXR1cm4gZmlsZS51cGxvYWQuY2h1bmtzW2ldO1xuICAgICAgICB9XG4gICAgfVxuICAgIC8vIFRoaXMgZnVuY3Rpb24gYWN0dWFsbHkgdXBsb2FkcyB0aGUgZmlsZShzKSB0byB0aGUgc2VydmVyLlxuICAgIC8vXG4gICAgLy8gIElmIGRhdGFCbG9ja3MgY29udGFpbnMgdGhlIGFjdHVhbCBkYXRhIHRvIHVwbG9hZCAobWVhbmluZywgdGhhdCB0aGlzIGNvdWxkXG4gICAgLy8gZWl0aGVyIGJlIHRyYW5zZm9ybWVkIGZpbGVzLCBvciBpbmRpdmlkdWFsIGNodW5rcyBmb3IgY2h1bmtlZCB1cGxvYWQpIHRoZW5cbiAgICAvLyB0aGV5IHdpbGwgYmUgdXNlZCBmb3IgdGhlIGFjdHVhbCBkYXRhIHRvIHVwbG9hZC5cbiAgICBfdXBsb2FkRGF0YShmaWxlcywgZGF0YUJsb2Nrcykge1xuICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XG4gICAgICAgIC8vIFB1dCB0aGUgeGhyIG9iamVjdCBpbiB0aGUgZmlsZSBvYmplY3RzIHRvIGJlIGFibGUgdG8gcmVmZXJlbmNlIGl0IGxhdGVyLlxuICAgICAgICBmb3IgKGxldCBmaWxlIG9mIGZpbGVzKWZpbGUueGhyID0geGhyO1xuICAgICAgICBpZiAoZmlsZXNbMF0udXBsb2FkLmNodW5rZWQpIC8vIFB1dCB0aGUgeGhyIG9iamVjdCBpbiB0aGUgcmlnaHQgY2h1bmsgb2JqZWN0LCBzbyBpdCBjYW4gYmUgYXNzb2NpYXRlZFxuICAgICAgICAvLyBsYXRlciwgYW5kIGZvdW5kIHdpdGggX2dldENodW5rLlxuICAgICAgICBmaWxlc1swXS51cGxvYWQuY2h1bmtzW2RhdGFCbG9ja3NbMF0uY2h1bmtJbmRleF0ueGhyID0geGhyO1xuICAgICAgICBsZXQgbWV0aG9kID0gdGhpcy5yZXNvbHZlT3B0aW9uKHRoaXMub3B0aW9ucy5tZXRob2QsIGZpbGVzLCBkYXRhQmxvY2tzKTtcbiAgICAgICAgbGV0IHVybCA9IHRoaXMucmVzb2x2ZU9wdGlvbih0aGlzLm9wdGlvbnMudXJsLCBmaWxlcywgZGF0YUJsb2Nrcyk7XG4gICAgICAgIHhoci5vcGVuKG1ldGhvZCwgdXJsLCB0cnVlKTtcbiAgICAgICAgLy8gU2V0dGluZyB0aGUgdGltZW91dCBhZnRlciBvcGVuIGJlY2F1c2Ugb2YgSUUxMSBpc3N1ZTogaHR0cHM6Ly9naXRsYWIuY29tL21lbm8vZHJvcHpvbmUvaXNzdWVzLzhcbiAgICAgICAgbGV0IHRpbWVvdXQgPSB0aGlzLnJlc29sdmVPcHRpb24odGhpcy5vcHRpb25zLnRpbWVvdXQsIGZpbGVzKTtcbiAgICAgICAgaWYgKHRpbWVvdXQpIHhoci50aW1lb3V0ID0gdGhpcy5yZXNvbHZlT3B0aW9uKHRoaXMub3B0aW9ucy50aW1lb3V0LCBmaWxlcyk7XG4gICAgICAgIC8vIEhhcyB0byBiZSBhZnRlciBgLm9wZW4oKWAuIFNlZSBodHRwczovL2dpdGh1Yi5jb20vZW55by9kcm9wem9uZS9pc3N1ZXMvMTc5XG4gICAgICAgIHhoci53aXRoQ3JlZGVudGlhbHMgPSAhIXRoaXMub3B0aW9ucy53aXRoQ3JlZGVudGlhbHM7XG4gICAgICAgIHhoci5vbmxvYWQgPSAoZSk9PntcbiAgICAgICAgICAgIHRoaXMuX2ZpbmlzaGVkVXBsb2FkaW5nKGZpbGVzLCB4aHIsIGUpO1xuICAgICAgICB9O1xuICAgICAgICB4aHIub250aW1lb3V0ID0gKCk9PntcbiAgICAgICAgICAgIHRoaXMuX2hhbmRsZVVwbG9hZEVycm9yKGZpbGVzLCB4aHIsIGBSZXF1ZXN0IHRpbWVkb3V0IGFmdGVyICR7dGhpcy5vcHRpb25zLnRpbWVvdXQgLyAxMDAwfSBzZWNvbmRzYCk7XG4gICAgICAgIH07XG4gICAgICAgIHhoci5vbmVycm9yID0gKCk9PntcbiAgICAgICAgICAgIHRoaXMuX2hhbmRsZVVwbG9hZEVycm9yKGZpbGVzLCB4aHIpO1xuICAgICAgICB9O1xuICAgICAgICAvLyBTb21lIGJyb3dzZXJzIGRvIG5vdCBoYXZlIHRoZSAudXBsb2FkIHByb3BlcnR5XG4gICAgICAgIGxldCBwcm9ncmVzc09iaiA9IHhoci51cGxvYWQgIT0gbnVsbCA/IHhoci51cGxvYWQgOiB4aHI7XG4gICAgICAgIHByb2dyZXNzT2JqLm9ucHJvZ3Jlc3MgPSAoZSk9PnRoaXMuX3VwZGF0ZUZpbGVzVXBsb2FkUHJvZ3Jlc3MoZmlsZXMsIHhociwgZSlcbiAgICAgICAgO1xuICAgICAgICBsZXQgaGVhZGVycyA9IHRoaXMub3B0aW9ucy5kZWZhdWx0SGVhZGVycyA/IHtcbiAgICAgICAgICAgIEFjY2VwdDogXCJhcHBsaWNhdGlvbi9qc29uXCIsXG4gICAgICAgICAgICBcIkNhY2hlLUNvbnRyb2xcIjogXCJuby1jYWNoZVwiLFxuICAgICAgICAgICAgXCJYLVJlcXVlc3RlZC1XaXRoXCI6IFwiWE1MSHR0cFJlcXVlc3RcIlxuICAgICAgICB9IDoge1xuICAgICAgICB9O1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLmJpbmFyeUJvZHkpIGhlYWRlcnNbXCJDb250ZW50LVR5cGVcIl0gPSBmaWxlc1swXS50eXBlO1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLmhlYWRlcnMpICRld0JLeSRqdXN0ZXh0ZW5kKGhlYWRlcnMsIHRoaXMub3B0aW9ucy5oZWFkZXJzKTtcbiAgICAgICAgZm9yKGxldCBoZWFkZXJOYW1lIGluIGhlYWRlcnMpe1xuICAgICAgICAgICAgbGV0IGhlYWRlclZhbHVlID0gaGVhZGVyc1toZWFkZXJOYW1lXTtcbiAgICAgICAgICAgIGlmIChoZWFkZXJWYWx1ZSkgeGhyLnNldFJlcXVlc3RIZWFkZXIoaGVhZGVyTmFtZSwgaGVhZGVyVmFsdWUpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMuYmluYXJ5Qm9keSkge1xuICAgICAgICAgICAgLy8gU2luY2UgdGhlIGZpbGUgaXMgZ29pbmcgdG8gYmUgc2VudCBhcyBiaW5hcnkgYm9keSwgaXQgZG9lc24ndCBtYWtlXG4gICAgICAgICAgICAvLyBhbnkgc2Vuc2UgdG8gZ2VuZXJhdGUgYEZvcm1EYXRhYCBmb3IgaXQuXG4gICAgICAgICAgICBmb3IgKGxldCBmaWxlIG9mIGZpbGVzKXRoaXMuZW1pdChcInNlbmRpbmdcIiwgZmlsZSwgeGhyKTtcbiAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUpIHRoaXMuZW1pdChcInNlbmRpbmdtdWx0aXBsZVwiLCBmaWxlcywgeGhyKTtcbiAgICAgICAgICAgIHRoaXMuc3VibWl0UmVxdWVzdCh4aHIsIG51bGwsIGZpbGVzKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGxldCBmb3JtRGF0YSA9IG5ldyBGb3JtRGF0YSgpO1xuICAgICAgICAgICAgLy8gQWRkaW5nIGFsbCBAb3B0aW9ucyBwYXJhbWV0ZXJzXG4gICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLnBhcmFtcykge1xuICAgICAgICAgICAgICAgIGxldCBhZGRpdGlvbmFsUGFyYW1zID0gdGhpcy5vcHRpb25zLnBhcmFtcztcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGFkZGl0aW9uYWxQYXJhbXMgPT09IFwiZnVuY3Rpb25cIikgYWRkaXRpb25hbFBhcmFtcyA9IGFkZGl0aW9uYWxQYXJhbXMuY2FsbCh0aGlzLCBmaWxlcywgeGhyLCBmaWxlc1swXS51cGxvYWQuY2h1bmtlZCA/IHRoaXMuX2dldENodW5rKGZpbGVzWzBdLCB4aHIpIDogbnVsbCk7XG4gICAgICAgICAgICAgICAgZm9yKGxldCBrZXkgaW4gYWRkaXRpb25hbFBhcmFtcyl7XG4gICAgICAgICAgICAgICAgICAgIGxldCB2YWx1ZSA9IGFkZGl0aW9uYWxQYXJhbXNba2V5XTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKEFycmF5LmlzQXJyYXkodmFsdWUpKSAvLyBUaGUgYWRkaXRpb25hbCBwYXJhbWV0ZXIgY29udGFpbnMgYW4gYXJyYXksXG4gICAgICAgICAgICAgICAgICAgIC8vIHNvIGxldHMgaXRlcmF0ZSBvdmVyIGl0IHRvIGF0dGFjaCBlYWNoIHZhbHVlXG4gICAgICAgICAgICAgICAgICAgIC8vIGluZGl2aWR1YWxseS5cbiAgICAgICAgICAgICAgICAgICAgZm9yKGxldCBpID0gMDsgaSA8IHZhbHVlLmxlbmd0aDsgaSsrKWZvcm1EYXRhLmFwcGVuZChrZXksIHZhbHVlW2ldKTtcbiAgICAgICAgICAgICAgICAgICAgZWxzZSBmb3JtRGF0YS5hcHBlbmQoa2V5LCB2YWx1ZSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgLy8gTGV0IHRoZSB1c2VyIGFkZCBhZGRpdGlvbmFsIGRhdGEgaWYgbmVjZXNzYXJ5XG4gICAgICAgICAgICBmb3IgKGxldCBmaWxlIG9mIGZpbGVzKXRoaXMuZW1pdChcInNlbmRpbmdcIiwgZmlsZSwgeGhyLCBmb3JtRGF0YSk7XG4gICAgICAgICAgICBpZiAodGhpcy5vcHRpb25zLnVwbG9hZE11bHRpcGxlKSB0aGlzLmVtaXQoXCJzZW5kaW5nbXVsdGlwbGVcIiwgZmlsZXMsIHhociwgZm9ybURhdGEpO1xuICAgICAgICAgICAgdGhpcy5fYWRkRm9ybUVsZW1lbnREYXRhKGZvcm1EYXRhKTtcbiAgICAgICAgICAgIC8vIEZpbmFsbHkgYWRkIHRoZSBmaWxlc1xuICAgICAgICAgICAgLy8gSGFzIHRvIGJlIGxhc3QgYmVjYXVzZSBzb21lIHNlcnZlcnMgKGVnOiBTMykgZXhwZWN0IHRoZSBmaWxlIHRvIGJlIHRoZSBsYXN0IHBhcmFtZXRlclxuICAgICAgICAgICAgZm9yKGxldCBpID0gMDsgaSA8IGRhdGFCbG9ja3MubGVuZ3RoOyBpKyspe1xuICAgICAgICAgICAgICAgIGxldCBkYXRhQmxvY2sgPSBkYXRhQmxvY2tzW2ldO1xuICAgICAgICAgICAgICAgIGZvcm1EYXRhLmFwcGVuZChkYXRhQmxvY2submFtZSwgZGF0YUJsb2NrLmRhdGEsIGRhdGFCbG9jay5maWxlbmFtZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB0aGlzLnN1Ym1pdFJlcXVlc3QoeGhyLCBmb3JtRGF0YSwgZmlsZXMpO1xuICAgICAgICB9XG4gICAgfVxuICAgIC8vIFRyYW5zZm9ybXMgYWxsIGZpbGVzIHdpdGggdGhpcy5vcHRpb25zLnRyYW5zZm9ybUZpbGUgYW5kIGludm9rZXMgZG9uZSB3aXRoIHRoZSB0cmFuc2Zvcm1lZCBmaWxlcyB3aGVuIGRvbmUuXG4gICAgX3RyYW5zZm9ybUZpbGVzKGZpbGVzLCBkb25lKSB7XG4gICAgICAgIGxldCB0cmFuc2Zvcm1lZEZpbGVzID0gW107XG4gICAgICAgIC8vIENsdW1zeSB3YXkgb2YgaGFuZGxpbmcgYXN5bmNocm9ub3VzIGNhbGxzLCB1bnRpbCBJIGdldCB0byBhZGQgYSBwcm9wZXIgRnV0dXJlIGxpYnJhcnkuXG4gICAgICAgIGxldCBkb25lQ291bnRlciA9IDA7XG4gICAgICAgIGZvcihsZXQgaSA9IDA7IGkgPCBmaWxlcy5sZW5ndGg7IGkrKyl0aGlzLm9wdGlvbnMudHJhbnNmb3JtRmlsZS5jYWxsKHRoaXMsIGZpbGVzW2ldLCAodHJhbnNmb3JtZWRGaWxlKT0+e1xuICAgICAgICAgICAgdHJhbnNmb3JtZWRGaWxlc1tpXSA9IHRyYW5zZm9ybWVkRmlsZTtcbiAgICAgICAgICAgIGlmICgrK2RvbmVDb3VudGVyID09PSBmaWxlcy5sZW5ndGgpIGRvbmUodHJhbnNmb3JtZWRGaWxlcyk7XG4gICAgICAgIH0pO1xuICAgIH1cbiAgICAvLyBUYWtlcyBjYXJlIG9mIGFkZGluZyBvdGhlciBpbnB1dCBlbGVtZW50cyBvZiB0aGUgZm9ybSB0byB0aGUgQUpBWCByZXF1ZXN0XG4gICAgX2FkZEZvcm1FbGVtZW50RGF0YShmb3JtRGF0YSkge1xuICAgICAgICAvLyBUYWtlIGNhcmUgb2Ygb3RoZXIgaW5wdXQgZWxlbWVudHNcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudC50YWdOYW1lID09PSBcIkZPUk1cIikgZm9yIChsZXQgaW5wdXQgb2YgdGhpcy5lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCJpbnB1dCwgdGV4dGFyZWEsIHNlbGVjdCwgYnV0dG9uXCIpKXtcbiAgICAgICAgICAgIGxldCBpbnB1dE5hbWUgPSBpbnB1dC5nZXRBdHRyaWJ1dGUoXCJuYW1lXCIpO1xuICAgICAgICAgICAgbGV0IGlucHV0VHlwZSA9IGlucHV0LmdldEF0dHJpYnV0ZShcInR5cGVcIik7XG4gICAgICAgICAgICBpZiAoaW5wdXRUeXBlKSBpbnB1dFR5cGUgPSBpbnB1dFR5cGUudG9Mb3dlckNhc2UoKTtcbiAgICAgICAgICAgIC8vIElmIHRoZSBpbnB1dCBkb2Vzbid0IGhhdmUgYSBuYW1lLCB3ZSBjYW4ndCB1c2UgaXQuXG4gICAgICAgICAgICBpZiAodHlwZW9mIGlucHV0TmFtZSA9PT0gXCJ1bmRlZmluZWRcIiB8fCBpbnB1dE5hbWUgPT09IG51bGwpIGNvbnRpbnVlO1xuICAgICAgICAgICAgaWYgKGlucHV0LnRhZ05hbWUgPT09IFwiU0VMRUNUXCIgJiYgaW5wdXQuaGFzQXR0cmlidXRlKFwibXVsdGlwbGVcIikpIHtcbiAgICAgICAgICAgICAgICAvLyBQb3NzaWJseSBtdWx0aXBsZSB2YWx1ZXNcbiAgICAgICAgICAgICAgICBmb3IgKGxldCBvcHRpb24gb2YgaW5wdXQub3B0aW9ucylpZiAob3B0aW9uLnNlbGVjdGVkKSBmb3JtRGF0YS5hcHBlbmQoaW5wdXROYW1lLCBvcHRpb24udmFsdWUpO1xuICAgICAgICAgICAgfSBlbHNlIGlmICghaW5wdXRUeXBlIHx8IGlucHV0VHlwZSAhPT0gXCJjaGVja2JveFwiICYmIGlucHV0VHlwZSAhPT0gXCJyYWRpb1wiIHx8IGlucHV0LmNoZWNrZWQpIGZvcm1EYXRhLmFwcGVuZChpbnB1dE5hbWUsIGlucHV0LnZhbHVlKTtcbiAgICAgICAgfVxuICAgIH1cbiAgICAvLyBJbnZva2VkIHdoZW4gdGhlcmUgaXMgbmV3IHByb2dyZXNzIGluZm9ybWF0aW9uIGFib3V0IGdpdmVuIGZpbGVzLlxuICAgIC8vIElmIGUgaXMgbm90IHByb3ZpZGVkLCBpdCBpcyBhc3N1bWVkIHRoYXQgdGhlIHVwbG9hZCBpcyBmaW5pc2hlZC5cbiAgICBfdXBkYXRlRmlsZXNVcGxvYWRQcm9ncmVzcyhmaWxlcywgeGhyLCBlKSB7XG4gICAgICAgIGlmICghZmlsZXNbMF0udXBsb2FkLmNodW5rZWQpIC8vIEhhbmRsZSBmaWxlIHVwbG9hZHMgd2l0aG91dCBjaHVua2luZ1xuICAgICAgICBmb3IgKGxldCBmaWxlIG9mIGZpbGVzKXtcbiAgICAgICAgICAgIGlmIChmaWxlLnVwbG9hZC50b3RhbCAmJiBmaWxlLnVwbG9hZC5ieXRlc1NlbnQgJiYgZmlsZS51cGxvYWQuYnl0ZXNTZW50ID09IGZpbGUudXBsb2FkLnRvdGFsKSBjb250aW51ZTtcbiAgICAgICAgICAgIGlmIChlKSB7XG4gICAgICAgICAgICAgICAgZmlsZS51cGxvYWQucHJvZ3Jlc3MgPSAxMDAgKiBlLmxvYWRlZCAvIGUudG90YWw7XG4gICAgICAgICAgICAgICAgZmlsZS51cGxvYWQudG90YWwgPSBlLnRvdGFsO1xuICAgICAgICAgICAgICAgIGZpbGUudXBsb2FkLmJ5dGVzU2VudCA9IGUubG9hZGVkO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvLyBObyBldmVudCwgc28gd2UncmUgYXQgMTAwJVxuICAgICAgICAgICAgICAgIGZpbGUudXBsb2FkLnByb2dyZXNzID0gMTAwO1xuICAgICAgICAgICAgICAgIGZpbGUudXBsb2FkLmJ5dGVzU2VudCA9IGZpbGUudXBsb2FkLnRvdGFsO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdGhpcy5lbWl0KFwidXBsb2FkcHJvZ3Jlc3NcIiwgZmlsZSwgZmlsZS51cGxvYWQucHJvZ3Jlc3MsIGZpbGUudXBsb2FkLmJ5dGVzU2VudCk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAvLyBIYW5kbGUgY2h1bmtlZCBmaWxlIHVwbG9hZHNcbiAgICAgICAgICAgIC8vIENodW5rZWQgdXBsb2FkIGlzIG5vdCBjb21wYXRpYmxlIHdpdGggdXBsb2FkaW5nIG11bHRpcGxlIGZpbGVzIGluIG9uZVxuICAgICAgICAgICAgLy8gcmVxdWVzdCwgc28gd2Uga25vdyB0aGVyZSdzIG9ubHkgb25lIGZpbGUuXG4gICAgICAgICAgICBsZXQgZmlsZSA9IGZpbGVzWzBdO1xuICAgICAgICAgICAgLy8gU2luY2UgdGhpcyBpcyBhIGNodW5rZWQgdXBsb2FkLCB3ZSBuZWVkIHRvIHVwZGF0ZSB0aGUgYXBwcm9wcmlhdGUgY2h1bmtcbiAgICAgICAgICAgIC8vIHByb2dyZXNzLlxuICAgICAgICAgICAgbGV0IGNodW5rID0gdGhpcy5fZ2V0Q2h1bmsoZmlsZSwgeGhyKTtcbiAgICAgICAgICAgIGlmIChlKSB7XG4gICAgICAgICAgICAgICAgY2h1bmsucHJvZ3Jlc3MgPSAxMDAgKiBlLmxvYWRlZCAvIGUudG90YWw7XG4gICAgICAgICAgICAgICAgY2h1bmsudG90YWwgPSBlLnRvdGFsO1xuICAgICAgICAgICAgICAgIGNodW5rLmJ5dGVzU2VudCA9IGUubG9hZGVkO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvLyBObyBldmVudCwgc28gd2UncmUgYXQgMTAwJVxuICAgICAgICAgICAgICAgIGNodW5rLnByb2dyZXNzID0gMTAwO1xuICAgICAgICAgICAgICAgIGNodW5rLmJ5dGVzU2VudCA9IGNodW5rLnRvdGFsO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgLy8gTm93IHRhbGx5IHRoZSAqZmlsZSogdXBsb2FkIHByb2dyZXNzIGZyb20gaXRzIGluZGl2aWR1YWwgY2h1bmtzXG4gICAgICAgICAgICBmaWxlLnVwbG9hZC5wcm9ncmVzcyA9IDA7XG4gICAgICAgICAgICBmaWxlLnVwbG9hZC50b3RhbCA9IDA7XG4gICAgICAgICAgICBmaWxlLnVwbG9hZC5ieXRlc1NlbnQgPSAwO1xuICAgICAgICAgICAgZm9yKGxldCBpID0gMDsgaSA8IGZpbGUudXBsb2FkLnRvdGFsQ2h1bmtDb3VudDsgaSsrKWlmIChmaWxlLnVwbG9hZC5jaHVua3NbaV0gJiYgdHlwZW9mIGZpbGUudXBsb2FkLmNodW5rc1tpXS5wcm9ncmVzcyAhPT0gXCJ1bmRlZmluZWRcIikge1xuICAgICAgICAgICAgICAgIGZpbGUudXBsb2FkLnByb2dyZXNzICs9IGZpbGUudXBsb2FkLmNodW5rc1tpXS5wcm9ncmVzcztcbiAgICAgICAgICAgICAgICBmaWxlLnVwbG9hZC50b3RhbCArPSBmaWxlLnVwbG9hZC5jaHVua3NbaV0udG90YWw7XG4gICAgICAgICAgICAgICAgZmlsZS51cGxvYWQuYnl0ZXNTZW50ICs9IGZpbGUudXBsb2FkLmNodW5rc1tpXS5ieXRlc1NlbnQ7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICAvLyBTaW5jZSB0aGUgcHJvY2VzcyBpcyBhIHBlcmNlbnRhZ2UsIHdlIG5lZWQgdG8gZGl2aWRlIGJ5IHRoZSBhbW91bnQgb2ZcbiAgICAgICAgICAgIC8vIGNodW5rcyB3ZSd2ZSB1c2VkLlxuICAgICAgICAgICAgZmlsZS51cGxvYWQucHJvZ3Jlc3MgPSBmaWxlLnVwbG9hZC5wcm9ncmVzcyAvIGZpbGUudXBsb2FkLnRvdGFsQ2h1bmtDb3VudDtcbiAgICAgICAgICAgIHRoaXMuZW1pdChcInVwbG9hZHByb2dyZXNzXCIsIGZpbGUsIGZpbGUudXBsb2FkLnByb2dyZXNzLCBmaWxlLnVwbG9hZC5ieXRlc1NlbnQpO1xuICAgICAgICB9XG4gICAgfVxuICAgIF9maW5pc2hlZFVwbG9hZGluZyhmaWxlcywgeGhyLCBlKSB7XG4gICAgICAgIGxldCByZXNwb25zZTtcbiAgICAgICAgaWYgKGZpbGVzWzBdLnN0YXR1cyA9PT0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5DQU5DRUxFRCkgcmV0dXJuO1xuICAgICAgICBpZiAoeGhyLnJlYWR5U3RhdGUgIT09IDQpIHJldHVybjtcbiAgICAgICAgaWYgKHhoci5yZXNwb25zZVR5cGUgIT09IFwiYXJyYXlidWZmZXJcIiAmJiB4aHIucmVzcG9uc2VUeXBlICE9PSBcImJsb2JcIikge1xuICAgICAgICAgICAgcmVzcG9uc2UgPSB4aHIucmVzcG9uc2VUZXh0O1xuICAgICAgICAgICAgaWYgKHhoci5nZXRSZXNwb25zZUhlYWRlcihcImNvbnRlbnQtdHlwZVwiKSAmJiB+eGhyLmdldFJlc3BvbnNlSGVhZGVyKFwiY29udGVudC10eXBlXCIpLmluZGV4T2YoXCJhcHBsaWNhdGlvbi9qc29uXCIpKSB0cnkge1xuICAgICAgICAgICAgICAgIHJlc3BvbnNlID0gSlNPTi5wYXJzZShyZXNwb25zZSk7XG4gICAgICAgICAgICB9IGNhdGNoIChlcnJvcikge1xuICAgICAgICAgICAgICAgIGUgPSBlcnJvcjtcbiAgICAgICAgICAgICAgICByZXNwb25zZSA9IFwiSW52YWxpZCBKU09OIHJlc3BvbnNlIGZyb20gc2VydmVyLlwiO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHRoaXMuX3VwZGF0ZUZpbGVzVXBsb2FkUHJvZ3Jlc3MoZmlsZXMsIHhocik7XG4gICAgICAgIGlmICghKDIwMCA8PSB4aHIuc3RhdHVzICYmIHhoci5zdGF0dXMgPCAzMDApKSB0aGlzLl9oYW5kbGVVcGxvYWRFcnJvcihmaWxlcywgeGhyLCByZXNwb25zZSk7XG4gICAgICAgIGVsc2UgaWYgKGZpbGVzWzBdLnVwbG9hZC5jaHVua2VkKSBmaWxlc1swXS51cGxvYWQuZmluaXNoZWRDaHVua1VwbG9hZCh0aGlzLl9nZXRDaHVuayhmaWxlc1swXSwgeGhyKSwgcmVzcG9uc2UpO1xuICAgICAgICBlbHNlIHRoaXMuX2ZpbmlzaGVkKGZpbGVzLCByZXNwb25zZSwgZSk7XG4gICAgfVxuICAgIF9oYW5kbGVVcGxvYWRFcnJvcihmaWxlcywgeGhyLCByZXNwb25zZSkge1xuICAgICAgICBpZiAoZmlsZXNbMF0uc3RhdHVzID09PSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkNBTkNFTEVEKSByZXR1cm47XG4gICAgICAgIGlmIChmaWxlc1swXS51cGxvYWQuY2h1bmtlZCAmJiB0aGlzLm9wdGlvbnMucmV0cnlDaHVua3MpIHtcbiAgICAgICAgICAgIGxldCBjaHVuayA9IHRoaXMuX2dldENodW5rKGZpbGVzWzBdLCB4aHIpO1xuICAgICAgICAgICAgaWYgKChjaHVuay5yZXRyaWVzKyspIDwgdGhpcy5vcHRpb25zLnJldHJ5Q2h1bmtzTGltaXQpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl91cGxvYWREYXRhKGZpbGVzLCBbXG4gICAgICAgICAgICAgICAgICAgIGNodW5rLmRhdGFCbG9ja1xuICAgICAgICAgICAgICAgIF0pO1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH0gZWxzZSBjb25zb2xlLndhcm4oXCJSZXRyaWVkIHRoaXMgY2h1bmsgdG9vIG9mdGVuLiBHaXZpbmcgdXAuXCIpO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMuX2Vycm9yUHJvY2Vzc2luZyhmaWxlcywgcmVzcG9uc2UgfHwgdGhpcy5vcHRpb25zLmRpY3RSZXNwb25zZUVycm9yLnJlcGxhY2UoXCJ7e3N0YXR1c0NvZGV9fVwiLCB4aHIuc3RhdHVzKSwgeGhyKTtcbiAgICB9XG4gICAgc3VibWl0UmVxdWVzdCh4aHIsIGZvcm1EYXRhLCBmaWxlcykge1xuICAgICAgICBpZiAoeGhyLnJlYWR5U3RhdGUgIT0gMSkge1xuICAgICAgICAgICAgY29uc29sZS53YXJuKFwiQ2Fubm90IHNlbmQgdGhpcyByZXF1ZXN0IGJlY2F1c2UgdGhlIFhNTEh0dHBSZXF1ZXN0LnJlYWR5U3RhdGUgaXMgbm90IE9QRU5FRC5cIik7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5iaW5hcnlCb2R5KSB7XG4gICAgICAgICAgICBpZiAoZmlsZXNbMF0udXBsb2FkLmNodW5rZWQpIHtcbiAgICAgICAgICAgICAgICBjb25zdCBjaHVuayA9IHRoaXMuX2dldENodW5rKGZpbGVzWzBdLCB4aHIpO1xuICAgICAgICAgICAgICAgIHhoci5zZW5kKGNodW5rLmRhdGFCbG9jay5kYXRhKTtcbiAgICAgICAgICAgIH0gZWxzZSB4aHIuc2VuZChmaWxlc1swXSk7XG4gICAgICAgIH0gZWxzZSB4aHIuc2VuZChmb3JtRGF0YSk7XG4gICAgfVxuICAgIC8vIENhbGxlZCBpbnRlcm5hbGx5IHdoZW4gcHJvY2Vzc2luZyBpcyBmaW5pc2hlZC5cbiAgICAvLyBJbmRpdmlkdWFsIGNhbGxiYWNrcyBoYXZlIHRvIGJlIGNhbGxlZCBpbiB0aGUgYXBwcm9wcmlhdGUgc2VjdGlvbnMuXG4gICAgX2ZpbmlzaGVkKGZpbGVzLCByZXNwb25zZVRleHQsIGUpIHtcbiAgICAgICAgZm9yIChsZXQgZmlsZSBvZiBmaWxlcyl7XG4gICAgICAgICAgICBmaWxlLnN0YXR1cyA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuU1VDQ0VTUztcbiAgICAgICAgICAgIHRoaXMuZW1pdChcInN1Y2Nlc3NcIiwgZmlsZSwgcmVzcG9uc2VUZXh0LCBlKTtcbiAgICAgICAgICAgIHRoaXMuZW1pdChcImNvbXBsZXRlXCIsIGZpbGUpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUpIHtcbiAgICAgICAgICAgIHRoaXMuZW1pdChcInN1Y2Nlc3NtdWx0aXBsZVwiLCBmaWxlcywgcmVzcG9uc2VUZXh0LCBlKTtcbiAgICAgICAgICAgIHRoaXMuZW1pdChcImNvbXBsZXRlbXVsdGlwbGVcIiwgZmlsZXMpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMuYXV0b1Byb2Nlc3NRdWV1ZSkgcmV0dXJuIHRoaXMucHJvY2Vzc1F1ZXVlKCk7XG4gICAgfVxuICAgIC8vIENhbGxlZCBpbnRlcm5hbGx5IHdoZW4gcHJvY2Vzc2luZyBpcyBmaW5pc2hlZC5cbiAgICAvLyBJbmRpdmlkdWFsIGNhbGxiYWNrcyBoYXZlIHRvIGJlIGNhbGxlZCBpbiB0aGUgYXBwcm9wcmlhdGUgc2VjdGlvbnMuXG4gICAgX2Vycm9yUHJvY2Vzc2luZyhmaWxlcywgbWVzc2FnZSwgeGhyKSB7XG4gICAgICAgIGZvciAobGV0IGZpbGUgb2YgZmlsZXMpe1xuICAgICAgICAgICAgZmlsZS5zdGF0dXMgPSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkVSUk9SO1xuICAgICAgICAgICAgdGhpcy5lbWl0KFwiZXJyb3JcIiwgZmlsZSwgbWVzc2FnZSwgeGhyKTtcbiAgICAgICAgICAgIHRoaXMuZW1pdChcImNvbXBsZXRlXCIsIGZpbGUpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMudXBsb2FkTXVsdGlwbGUpIHtcbiAgICAgICAgICAgIHRoaXMuZW1pdChcImVycm9ybXVsdGlwbGVcIiwgZmlsZXMsIG1lc3NhZ2UsIHhocik7XG4gICAgICAgICAgICB0aGlzLmVtaXQoXCJjb21wbGV0ZW11bHRpcGxlXCIsIGZpbGVzKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAodGhpcy5vcHRpb25zLmF1dG9Qcm9jZXNzUXVldWUpIHJldHVybiB0aGlzLnByb2Nlc3NRdWV1ZSgpO1xuICAgIH1cbiAgICBzdGF0aWMgdXVpZHY0KCkge1xuICAgICAgICByZXR1cm4gXCJ4eHh4eHh4eC14eHh4LTR4eHgteXh4eC14eHh4eHh4eHh4eHhcIi5yZXBsYWNlKC9beHldL2csIGZ1bmN0aW9uKGMpIHtcbiAgICAgICAgICAgIGxldCByID0gTWF0aC5yYW5kb20oKSAqIDE2IHwgMCwgdiA9IGMgPT09IFwieFwiID8gciA6IHIgJiAzIHwgODtcbiAgICAgICAgICAgIHJldHVybiB2LnRvU3RyaW5nKDE2KTtcbiAgICAgICAgfSk7XG4gICAgfVxuICAgIGNvbnN0cnVjdG9yKGVsLCBvcHRpb25zKXtcbiAgICAgICAgc3VwZXIoKTtcbiAgICAgICAgbGV0IGZhbGxiYWNrLCBsZWZ0O1xuICAgICAgICB0aGlzLmVsZW1lbnQgPSBlbDtcbiAgICAgICAgdGhpcy5jbGlja2FibGVFbGVtZW50cyA9IFtdO1xuICAgICAgICB0aGlzLmxpc3RlbmVycyA9IFtdO1xuICAgICAgICB0aGlzLmZpbGVzID0gW107IC8vIEFsbCBmaWxlc1xuICAgICAgICBpZiAodHlwZW9mIHRoaXMuZWxlbWVudCA9PT0gXCJzdHJpbmdcIikgdGhpcy5lbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3Rvcih0aGlzLmVsZW1lbnQpO1xuICAgICAgICAvLyBOb3QgY2hlY2tpbmcgaWYgaW5zdGFuY2Ugb2YgSFRNTEVsZW1lbnQgb3IgRWxlbWVudCBzaW5jZSBJRTkgaXMgZXh0cmVtZWx5IHdlaXJkLlxuICAgICAgICBpZiAoIXRoaXMuZWxlbWVudCB8fCB0aGlzLmVsZW1lbnQubm9kZVR5cGUgPT0gbnVsbCkgdGhyb3cgbmV3IEVycm9yKFwiSW52YWxpZCBkcm9wem9uZSBlbGVtZW50LlwiKTtcbiAgICAgICAgaWYgKHRoaXMuZWxlbWVudC5kcm9wem9uZSkgdGhyb3cgbmV3IEVycm9yKFwiRHJvcHpvbmUgYWxyZWFkeSBhdHRhY2hlZC5cIik7XG4gICAgICAgIC8vIE5vdyBhZGQgdGhpcyBkcm9wem9uZSB0byB0aGUgaW5zdGFuY2VzLlxuICAgICAgICAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5Lmluc3RhbmNlcy5wdXNoKHRoaXMpO1xuICAgICAgICAvLyBQdXQgdGhlIGRyb3B6b25lIGluc2lkZSB0aGUgZWxlbWVudCBpdHNlbGYuXG4gICAgICAgIHRoaXMuZWxlbWVudC5kcm9wem9uZSA9IHRoaXM7XG4gICAgICAgIGxldCBlbGVtZW50T3B0aW9ucyA9IChsZWZ0ID0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5vcHRpb25zRm9yRWxlbWVudCh0aGlzLmVsZW1lbnQpKSAhPSBudWxsID8gbGVmdCA6IHtcbiAgICAgICAgfTtcbiAgICAgICAgdGhpcy5vcHRpb25zID0gJGV3Qkt5JGp1c3RleHRlbmQodHJ1ZSwge1xuICAgICAgICB9LCAkNGNhMzY3MTgyNzc2ZjgwYiRleHBvcnQkMmUyYmNkODczOWFlMDM5LCBlbGVtZW50T3B0aW9ucywgb3B0aW9ucyAhPSBudWxsID8gb3B0aW9ucyA6IHtcbiAgICAgICAgfSk7XG4gICAgICAgIHRoaXMub3B0aW9ucy5wcmV2aWV3VGVtcGxhdGUgPSB0aGlzLm9wdGlvbnMucHJldmlld1RlbXBsYXRlLnJlcGxhY2UoL1xcbiovZywgXCJcIik7XG4gICAgICAgIC8vIElmIHRoZSBicm93c2VyIGZhaWxlZCwganVzdCBjYWxsIHRoZSBmYWxsYmFjayBhbmQgbGVhdmVcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5mb3JjZUZhbGxiYWNrIHx8ICEkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmlzQnJvd3NlclN1cHBvcnRlZCgpKSByZXR1cm4gdGhpcy5vcHRpb25zLmZhbGxiYWNrLmNhbGwodGhpcyk7XG4gICAgICAgIC8vIEBvcHRpb25zLnVybCA9IEBlbGVtZW50LmdldEF0dHJpYnV0ZSBcImFjdGlvblwiIHVubGVzcyBAb3B0aW9ucy51cmw/XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMudXJsID09IG51bGwpIHRoaXMub3B0aW9ucy51cmwgPSB0aGlzLmVsZW1lbnQuZ2V0QXR0cmlidXRlKFwiYWN0aW9uXCIpO1xuICAgICAgICBpZiAoIXRoaXMub3B0aW9ucy51cmwpIHRocm93IG5ldyBFcnJvcihcIk5vIFVSTCBwcm92aWRlZC5cIik7XG4gICAgICAgIGlmICh0aGlzLm9wdGlvbnMuYWNjZXB0ZWRGaWxlcyAmJiB0aGlzLm9wdGlvbnMuYWNjZXB0ZWRNaW1lVHlwZXMpIHRocm93IG5ldyBFcnJvcihcIllvdSBjYW4ndCBwcm92aWRlIGJvdGggJ2FjY2VwdGVkRmlsZXMnIGFuZCAnYWNjZXB0ZWRNaW1lVHlwZXMnLiAnYWNjZXB0ZWRNaW1lVHlwZXMnIGlzIGRlcHJlY2F0ZWQuXCIpO1xuICAgICAgICBpZiAodGhpcy5vcHRpb25zLnVwbG9hZE11bHRpcGxlICYmIHRoaXMub3B0aW9ucy5jaHVua2luZykgdGhyb3cgbmV3IEVycm9yKFwiWW91IGNhbm5vdCBzZXQgYm90aDogdXBsb2FkTXVsdGlwbGUgYW5kIGNodW5raW5nLlwiKTtcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5iaW5hcnlCb2R5ICYmIHRoaXMub3B0aW9ucy51cGxvYWRNdWx0aXBsZSkgdGhyb3cgbmV3IEVycm9yKFwiWW91IGNhbm5vdCBzZXQgYm90aDogYmluYXJ5Qm9keSBhbmQgdXBsb2FkTXVsdGlwbGUuXCIpO1xuICAgICAgICAvLyBCYWNrd2FyZHMgY29tcGF0aWJpbGl0eVxuICAgICAgICBpZiAodGhpcy5vcHRpb25zLmFjY2VwdGVkTWltZVR5cGVzKSB7XG4gICAgICAgICAgICB0aGlzLm9wdGlvbnMuYWNjZXB0ZWRGaWxlcyA9IHRoaXMub3B0aW9ucy5hY2NlcHRlZE1pbWVUeXBlcztcbiAgICAgICAgICAgIGRlbGV0ZSB0aGlzLm9wdGlvbnMuYWNjZXB0ZWRNaW1lVHlwZXM7XG4gICAgICAgIH1cbiAgICAgICAgLy8gQmFja3dhcmRzIGNvbXBhdGliaWxpdHlcbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5yZW5hbWVGaWxlbmFtZSAhPSBudWxsKSB0aGlzLm9wdGlvbnMucmVuYW1lRmlsZSA9IChmaWxlKT0+dGhpcy5vcHRpb25zLnJlbmFtZUZpbGVuYW1lLmNhbGwodGhpcywgZmlsZS5uYW1lLCBmaWxlKVxuICAgICAgICA7XG4gICAgICAgIGlmICh0eXBlb2YgdGhpcy5vcHRpb25zLm1ldGhvZCA9PT0gXCJzdHJpbmdcIikgdGhpcy5vcHRpb25zLm1ldGhvZCA9IHRoaXMub3B0aW9ucy5tZXRob2QudG9VcHBlckNhc2UoKTtcbiAgICAgICAgaWYgKChmYWxsYmFjayA9IHRoaXMuZ2V0RXhpc3RpbmdGYWxsYmFjaygpKSAmJiBmYWxsYmFjay5wYXJlbnROb2RlKSAvLyBSZW1vdmUgdGhlIGZhbGxiYWNrXG4gICAgICAgIGZhbGxiYWNrLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoZmFsbGJhY2spO1xuICAgICAgICAvLyBEaXNwbGF5IHByZXZpZXdzIGluIHRoZSBwcmV2aWV3c0NvbnRhaW5lciBlbGVtZW50IG9yIHRoZSBEcm9wem9uZSBlbGVtZW50IHVubGVzcyBleHBsaWNpdGx5IHNldCB0byBmYWxzZVxuICAgICAgICBpZiAodGhpcy5vcHRpb25zLnByZXZpZXdzQ29udGFpbmVyICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5wcmV2aWV3c0NvbnRhaW5lcikgdGhpcy5wcmV2aWV3c0NvbnRhaW5lciA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuZ2V0RWxlbWVudCh0aGlzLm9wdGlvbnMucHJldmlld3NDb250YWluZXIsIFwicHJldmlld3NDb250YWluZXJcIik7XG4gICAgICAgICAgICBlbHNlIHRoaXMucHJldmlld3NDb250YWluZXIgPSB0aGlzLmVsZW1lbnQ7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHRoaXMub3B0aW9ucy5jbGlja2FibGUpIHtcbiAgICAgICAgICAgIGlmICh0aGlzLm9wdGlvbnMuY2xpY2thYmxlID09PSB0cnVlKSB0aGlzLmNsaWNrYWJsZUVsZW1lbnRzID0gW1xuICAgICAgICAgICAgICAgIHRoaXMuZWxlbWVudFxuICAgICAgICAgICAgXTtcbiAgICAgICAgICAgIGVsc2UgdGhpcy5jbGlja2FibGVFbGVtZW50cyA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuZ2V0RWxlbWVudHModGhpcy5vcHRpb25zLmNsaWNrYWJsZSwgXCJjbGlja2FibGVcIik7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5pbml0KCk7XG4gICAgfVxufVxuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5pbml0Q2xhc3MoKTtcbi8vIFRoaXMgaXMgYSBtYXAgb2Ygb3B0aW9ucyBmb3IgeW91ciBkaWZmZXJlbnQgZHJvcHpvbmVzLiBBZGQgY29uZmlndXJhdGlvbnNcbi8vIHRvIHRoaXMgb2JqZWN0IGZvciB5b3VyIGRpZmZlcmVudCBkcm9wem9uZSBlbGVtZW5zLlxuLy9cbi8vIEV4YW1wbGU6XG4vL1xuLy8gICAgIERyb3B6b25lLm9wdGlvbnMubXlEcm9wem9uZUVsZW1lbnRJZCA9IHsgbWF4RmlsZXNpemU6IDEgfTtcbi8vXG4vLyBBbmQgaW4gaHRtbDpcbi8vXG4vLyAgICAgPGZvcm0gYWN0aW9uPVwiL3VwbG9hZFwiIGlkPVwibXktZHJvcHpvbmUtZWxlbWVudC1pZFwiIGNsYXNzPVwiZHJvcHpvbmVcIj48L2Zvcm0+XG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5Lm9wdGlvbnMgPSB7XG59O1xuLy8gUmV0dXJucyB0aGUgb3B0aW9ucyBmb3IgYW4gZWxlbWVudCBvciB1bmRlZmluZWQgaWYgbm9uZSBhdmFpbGFibGUuXG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5Lm9wdGlvbnNGb3JFbGVtZW50ID0gZnVuY3Rpb24oZWxlbWVudCkge1xuICAgIC8vIEdldCB0aGUgYERyb3B6b25lLm9wdGlvbnMuZWxlbWVudElkYCBmb3IgdGhpcyBlbGVtZW50IGlmIGl0IGV4aXN0c1xuICAgIGlmIChlbGVtZW50LmdldEF0dHJpYnV0ZShcImlkXCIpKSByZXR1cm4gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5vcHRpb25zWyQzZWQyNjlmMmYwZmIyMjRiJHZhciRjYW1lbGl6ZShlbGVtZW50LmdldEF0dHJpYnV0ZShcImlkXCIpKV07XG4gICAgZWxzZSByZXR1cm4gdW5kZWZpbmVkO1xufTtcbi8vIEhvbGRzIGEgbGlzdCBvZiBhbGwgZHJvcHpvbmUgaW5zdGFuY2VzXG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5Lmluc3RhbmNlcyA9IFtdO1xuLy8gUmV0dXJucyB0aGUgZHJvcHpvbmUgZm9yIGdpdmVuIGVsZW1lbnQgaWYgYW55XG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmZvckVsZW1lbnQgPSBmdW5jdGlvbihlbGVtZW50KSB7XG4gICAgaWYgKHR5cGVvZiBlbGVtZW50ID09PSBcInN0cmluZ1wiKSBlbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihlbGVtZW50KTtcbiAgICBpZiAoKGVsZW1lbnQgIT0gbnVsbCA/IGVsZW1lbnQuZHJvcHpvbmUgOiB1bmRlZmluZWQpID09IG51bGwpIHRocm93IG5ldyBFcnJvcihcIk5vIERyb3B6b25lIGZvdW5kIGZvciBnaXZlbiBlbGVtZW50LiBUaGlzIGlzIHByb2JhYmx5IGJlY2F1c2UgeW91J3JlIHRyeWluZyB0byBhY2Nlc3MgaXQgYmVmb3JlIERyb3B6b25lIGhhZCB0aGUgdGltZSB0byBpbml0aWFsaXplLiBVc2UgdGhlIGBpbml0YCBvcHRpb24gdG8gc2V0dXAgYW55IGFkZGl0aW9uYWwgb2JzZXJ2ZXJzIG9uIHlvdXIgRHJvcHpvbmUuXCIpO1xuICAgIHJldHVybiBlbGVtZW50LmRyb3B6b25lO1xufTtcbi8vIExvb2tzIGZvciBhbGwgLmRyb3B6b25lIGVsZW1lbnRzIGFuZCBjcmVhdGVzIGEgZHJvcHpvbmUgZm9yIHRoZW1cbiQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuZGlzY292ZXIgPSBmdW5jdGlvbigpIHtcbiAgICBsZXQgZHJvcHpvbmVzO1xuICAgIGlmIChkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKSBkcm9wem9uZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiLmRyb3B6b25lXCIpO1xuICAgIGVsc2Uge1xuICAgICAgICBkcm9wem9uZXMgPSBbXTtcbiAgICAgICAgLy8gSUUgOihcbiAgICAgICAgbGV0IGNoZWNrRWxlbWVudHMgPSAoZWxlbWVudHMpPT4oKCk9PntcbiAgICAgICAgICAgICAgICBsZXQgcmVzdWx0ID0gW107XG4gICAgICAgICAgICAgICAgZm9yIChsZXQgZWwgb2YgZWxlbWVudHMpaWYgKC8oXnwgKWRyb3B6b25lKCR8ICkvLnRlc3QoZWwuY2xhc3NOYW1lKSkgcmVzdWx0LnB1c2goZHJvcHpvbmVzLnB1c2goZWwpKTtcbiAgICAgICAgICAgICAgICBlbHNlIHJlc3VsdC5wdXNoKHVuZGVmaW5lZCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlc3VsdDtcbiAgICAgICAgICAgIH0pKClcbiAgICAgICAgO1xuICAgICAgICBjaGVja0VsZW1lbnRzKGRvY3VtZW50LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpKTtcbiAgICAgICAgY2hlY2tFbGVtZW50cyhkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZShcImZvcm1cIikpO1xuICAgIH1cbiAgICByZXR1cm4gKCgpPT57XG4gICAgICAgIGxldCByZXN1bHQgPSBbXTtcbiAgICAgICAgZm9yIChsZXQgZHJvcHpvbmUgb2YgZHJvcHpvbmVzKS8vIENyZWF0ZSBhIGRyb3B6b25lIHVubGVzcyBhdXRvIGRpc2NvdmVyIGhhcyBiZWVuIGRpc2FibGVkIGZvciBzcGVjaWZpYyBlbGVtZW50XG4gICAgICAgIGlmICgkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5Lm9wdGlvbnNGb3JFbGVtZW50KGRyb3B6b25lKSAhPT0gZmFsc2UpIHJlc3VsdC5wdXNoKG5ldyAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5KGRyb3B6b25lKSk7XG4gICAgICAgIGVsc2UgcmVzdWx0LnB1c2godW5kZWZpbmVkKTtcbiAgICAgICAgcmV0dXJuIHJlc3VsdDtcbiAgICB9KSgpO1xufTtcbi8vIFNvbWUgYnJvd3NlcnMgc3VwcG9ydCBkcmFnIGFuZCBkcm9nIGZ1bmN0aW9uYWxpdHksIGJ1dCBub3QgY29ycmVjdGx5LlxuLy9cbi8vIFNvIEkgY3JlYXRlZCBhIGJsb2NrbGlzdCBvZiB1c2VyQWdlbnRzLiBZZXMsIHllcy4gQnJvd3NlciBzbmlmZmluZywgSSBrbm93LlxuLy8gQnV0IHdoYXQgdG8gZG8gd2hlbiBicm93c2VycyAqdGhlb3JldGljYWxseSogc3VwcG9ydCBhbiBBUEksIGJ1dCBjcmFzaFxuLy8gd2hlbiB1c2luZyBpdC5cbi8vXG4vLyBUaGlzIGlzIGEgbGlzdCBvZiByZWd1bGFyIGV4cHJlc3Npb25zIHRlc3RlZCBhZ2FpbnN0IG5hdmlnYXRvci51c2VyQWdlbnRcbi8vXG4vLyAqKiBJdCBzaG91bGQgb25seSBiZSB1c2VkIG9uIGJyb3dzZXIgdGhhdCAqZG8qIHN1cHBvcnQgdGhlIEFQSSwgYnV0XG4vLyBpbmNvcnJlY3RseSAqKlxuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5ibG9ja2VkQnJvd3NlcnMgPSBbXG4gICAgLy8gVGhlIG1hYyBvcyBhbmQgd2luZG93cyBwaG9uZSB2ZXJzaW9uIG9mIG9wZXJhIDEyIHNlZW1zIHRvIGhhdmUgYSBwcm9ibGVtIHdpdGggdGhlIEZpbGUgZHJhZyduJ2Ryb3AgQVBJLlxuICAgIC9vcGVyYS4qKE1hY2ludG9zaHxXaW5kb3dzIFBob25lKS4qdmVyc2lvblxcLzEyL2ksIFxuXTtcbi8vIENoZWNrcyBpZiB0aGUgYnJvd3NlciBpcyBzdXBwb3J0ZWRcbiQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuaXNCcm93c2VyU3VwcG9ydGVkID0gZnVuY3Rpb24oKSB7XG4gICAgbGV0IGNhcGFibGVCcm93c2VyID0gdHJ1ZTtcbiAgICBpZiAod2luZG93LkZpbGUgJiYgd2luZG93LkZpbGVSZWFkZXIgJiYgd2luZG93LkZpbGVMaXN0ICYmIHdpbmRvdy5CbG9iICYmIHdpbmRvdy5Gb3JtRGF0YSAmJiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKSB7XG4gICAgICAgIGlmICghKFwiY2xhc3NMaXN0XCIgaW4gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImFcIikpKSBjYXBhYmxlQnJvd3NlciA9IGZhbHNlO1xuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGlmICgkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmJsYWNrbGlzdGVkQnJvd3NlcnMgIT09IHVuZGVmaW5lZCkgLy8gU2luY2UgdGhpcyBoYXMgYmVlbiByZW5hbWVkLCB0aGlzIG1ha2VzIHN1cmUgd2UgZG9uJ3QgYnJlYWsgb2xkZXJcbiAgICAgICAgICAgIC8vIGNvbmZpZ3VyYXRpb24uXG4gICAgICAgICAgICAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmJsb2NrZWRCcm93c2VycyA9ICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuYmxhY2tsaXN0ZWRCcm93c2VycztcbiAgICAgICAgICAgIC8vIFRoZSBicm93c2VyIHN1cHBvcnRzIHRoZSBBUEksIGJ1dCBtYXkgYmUgYmxvY2tlZC5cbiAgICAgICAgICAgIGZvciAobGV0IHJlZ2V4IG9mICQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuYmxvY2tlZEJyb3dzZXJzKWlmIChyZWdleC50ZXN0KG5hdmlnYXRvci51c2VyQWdlbnQpKSB7XG4gICAgICAgICAgICAgICAgY2FwYWJsZUJyb3dzZXIgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0gZWxzZSBjYXBhYmxlQnJvd3NlciA9IGZhbHNlO1xuICAgIHJldHVybiBjYXBhYmxlQnJvd3Nlcjtcbn07XG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LmRhdGFVUkl0b0Jsb2IgPSBmdW5jdGlvbihkYXRhVVJJKSB7XG4gICAgLy8gY29udmVydCBiYXNlNjQgdG8gcmF3IGJpbmFyeSBkYXRhIGhlbGQgaW4gYSBzdHJpbmdcbiAgICAvLyBkb2Vzbid0IGhhbmRsZSBVUkxFbmNvZGVkIERhdGFVUklzIC0gc2VlIFNPIGFuc3dlciAjNjg1MDI3NiBmb3IgY29kZSB0aGF0IGRvZXMgdGhpc1xuICAgIGxldCBieXRlU3RyaW5nID0gYXRvYihkYXRhVVJJLnNwbGl0KFwiLFwiKVsxXSk7XG4gICAgLy8gc2VwYXJhdGUgb3V0IHRoZSBtaW1lIGNvbXBvbmVudFxuICAgIGxldCBtaW1lU3RyaW5nID0gZGF0YVVSSS5zcGxpdChcIixcIilbMF0uc3BsaXQoXCI6XCIpWzFdLnNwbGl0KFwiO1wiKVswXTtcbiAgICAvLyB3cml0ZSB0aGUgYnl0ZXMgb2YgdGhlIHN0cmluZyB0byBhbiBBcnJheUJ1ZmZlclxuICAgIGxldCBhYiA9IG5ldyBBcnJheUJ1ZmZlcihieXRlU3RyaW5nLmxlbmd0aCk7XG4gICAgbGV0IGlhID0gbmV3IFVpbnQ4QXJyYXkoYWIpO1xuICAgIGZvcihsZXQgaSA9IDAsIGVuZCA9IGJ5dGVTdHJpbmcubGVuZ3RoLCBhc2MgPSAwIDw9IGVuZDsgYXNjID8gaSA8PSBlbmQgOiBpID49IGVuZDsgYXNjID8gaSsrIDogaS0tKWlhW2ldID0gYnl0ZVN0cmluZy5jaGFyQ29kZUF0KGkpO1xuICAgIC8vIHdyaXRlIHRoZSBBcnJheUJ1ZmZlciB0byBhIGJsb2JcbiAgICByZXR1cm4gbmV3IEJsb2IoW1xuICAgICAgICBhYlxuICAgIF0sIHtcbiAgICAgICAgdHlwZTogbWltZVN0cmluZ1xuICAgIH0pO1xufTtcbi8vIFJldHVybnMgYW4gYXJyYXkgd2l0aG91dCB0aGUgcmVqZWN0ZWQgaXRlbVxuY29uc3QgJDNlZDI2OWYyZjBmYjIyNGIkdmFyJHdpdGhvdXQgPSAobGlzdCwgcmVqZWN0ZWRJdGVtKT0+bGlzdC5maWx0ZXIoKGl0ZW0pPT5pdGVtICE9PSByZWplY3RlZEl0ZW1cbiAgICApLm1hcCgoaXRlbSk9Pml0ZW1cbiAgICApXG47XG4vLyBhYmMtZGVmX2doaSAtPiBhYmNEZWZHaGlcbmNvbnN0ICQzZWQyNjlmMmYwZmIyMjRiJHZhciRjYW1lbGl6ZSA9IChzdHIpPT5zdHIucmVwbGFjZSgvW1xcLV9dKFxcdykvZywgKG1hdGNoKT0+bWF0Y2guY2hhckF0KDEpLnRvVXBwZXJDYXNlKClcbiAgICApXG47XG4vLyBDcmVhdGVzIGFuIGVsZW1lbnQgZnJvbSBzdHJpbmdcbiQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuY3JlYXRlRWxlbWVudCA9IGZ1bmN0aW9uKHN0cmluZykge1xuICAgIGxldCBkaXYgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICAgIGRpdi5pbm5lckhUTUwgPSBzdHJpbmc7XG4gICAgcmV0dXJuIGRpdi5jaGlsZE5vZGVzWzBdO1xufTtcbi8vIFRlc3RzIGlmIGdpdmVuIGVsZW1lbnQgaXMgaW5zaWRlIChvciBzaW1wbHkgaXMpIHRoZSBjb250YWluZXJcbiQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuZWxlbWVudEluc2lkZSA9IGZ1bmN0aW9uKGVsZW1lbnQsIGNvbnRhaW5lcikge1xuICAgIGlmIChlbGVtZW50ID09PSBjb250YWluZXIpIHJldHVybiB0cnVlO1xuICAgICAvLyBDb2ZmZWVzY3JpcHQgZG9lc24ndCBzdXBwb3J0IGRvL3doaWxlIGxvb3BzXG4gICAgd2hpbGUoZWxlbWVudCA9IGVsZW1lbnQucGFyZW50Tm9kZSl7XG4gICAgICAgIGlmIChlbGVtZW50ID09PSBjb250YWluZXIpIHJldHVybiB0cnVlO1xuICAgIH1cbiAgICByZXR1cm4gZmFsc2U7XG59O1xuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5nZXRFbGVtZW50ID0gZnVuY3Rpb24oZWwsIG5hbWUpIHtcbiAgICBsZXQgZWxlbWVudDtcbiAgICBpZiAodHlwZW9mIGVsID09PSBcInN0cmluZ1wiKSBlbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihlbCk7XG4gICAgZWxzZSBpZiAoZWwubm9kZVR5cGUgIT0gbnVsbCkgZWxlbWVudCA9IGVsO1xuICAgIGlmIChlbGVtZW50ID09IG51bGwpIHRocm93IG5ldyBFcnJvcihgSW52YWxpZCBcXGAke25hbWV9XFxgIG9wdGlvbiBwcm92aWRlZC4gUGxlYXNlIHByb3ZpZGUgYSBDU1Mgc2VsZWN0b3Igb3IgYSBwbGFpbiBIVE1MIGVsZW1lbnQuYCk7XG4gICAgcmV0dXJuIGVsZW1lbnQ7XG59O1xuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5nZXRFbGVtZW50cyA9IGZ1bmN0aW9uKGVscywgbmFtZSkge1xuICAgIGxldCBlbCwgZWxlbWVudHM7XG4gICAgaWYgKGVscyBpbnN0YW5jZW9mIEFycmF5KSB7XG4gICAgICAgIGVsZW1lbnRzID0gW107XG4gICAgICAgIHRyeSB7XG4gICAgICAgICAgICBmb3IgKGVsIG9mIGVscyllbGVtZW50cy5wdXNoKHRoaXMuZ2V0RWxlbWVudChlbCwgbmFtZSkpO1xuICAgICAgICB9IGNhdGNoIChlKSB7XG4gICAgICAgICAgICBlbGVtZW50cyA9IG51bGw7XG4gICAgICAgIH1cbiAgICB9IGVsc2UgaWYgKHR5cGVvZiBlbHMgPT09IFwic3RyaW5nXCIpIHtcbiAgICAgICAgZWxlbWVudHMgPSBbXTtcbiAgICAgICAgZm9yIChlbCBvZiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKGVscykpZWxlbWVudHMucHVzaChlbCk7XG4gICAgfSBlbHNlIGlmIChlbHMubm9kZVR5cGUgIT0gbnVsbCkgZWxlbWVudHMgPSBbXG4gICAgICAgIGVsc1xuICAgIF07XG4gICAgaWYgKGVsZW1lbnRzID09IG51bGwgfHwgIWVsZW1lbnRzLmxlbmd0aCkgdGhyb3cgbmV3IEVycm9yKGBJbnZhbGlkIFxcYCR7bmFtZX1cXGAgb3B0aW9uIHByb3ZpZGVkLiBQbGVhc2UgcHJvdmlkZSBhIENTUyBzZWxlY3RvciwgYSBwbGFpbiBIVE1MIGVsZW1lbnQgb3IgYSBsaXN0IG9mIHRob3NlLmApO1xuICAgIHJldHVybiBlbGVtZW50cztcbn07XG4vLyBBc2tzIHRoZSB1c2VyIHRoZSBxdWVzdGlvbiBhbmQgY2FsbHMgYWNjZXB0ZWQgb3IgcmVqZWN0ZWQgYWNjb3JkaW5nbHlcbi8vXG4vLyBUaGUgZGVmYXVsdCBpbXBsZW1lbnRhdGlvbiBqdXN0IHVzZXMgYHdpbmRvdy5jb25maXJtYCBhbmQgdGhlbiBjYWxscyB0aGVcbi8vIGFwcHJvcHJpYXRlIGNhbGxiYWNrLlxuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5jb25maXJtID0gZnVuY3Rpb24ocXVlc3Rpb24sIGFjY2VwdGVkLCByZWplY3RlZCkge1xuICAgIGlmICh3aW5kb3cuY29uZmlybShxdWVzdGlvbikpIHJldHVybiBhY2NlcHRlZCgpO1xuICAgIGVsc2UgaWYgKHJlamVjdGVkICE9IG51bGwpIHJldHVybiByZWplY3RlZCgpO1xufTtcbi8vIFZhbGlkYXRlcyB0aGUgbWltZSB0eXBlIGxpa2UgdGhpczpcbi8vXG4vLyBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL0hUTUwvRWxlbWVudC9pbnB1dCNhdHRyLWFjY2VwdFxuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5pc1ZhbGlkRmlsZSA9IGZ1bmN0aW9uKGZpbGUsIGFjY2VwdGVkRmlsZXMpIHtcbiAgICBpZiAoIWFjY2VwdGVkRmlsZXMpIHJldHVybiB0cnVlO1xuICAgICAvLyBJZiB0aGVyZSBhcmUgbm8gYWNjZXB0ZWQgbWltZSB0eXBlcywgaXQncyBPS1xuICAgIGFjY2VwdGVkRmlsZXMgPSBhY2NlcHRlZEZpbGVzLnNwbGl0KFwiLFwiKTtcbiAgICBsZXQgbWltZVR5cGUgPSBmaWxlLnR5cGU7XG4gICAgbGV0IGJhc2VNaW1lVHlwZSA9IG1pbWVUeXBlLnJlcGxhY2UoL1xcLy4qJC8sIFwiXCIpO1xuICAgIGZvciAobGV0IHZhbGlkVHlwZSBvZiBhY2NlcHRlZEZpbGVzKXtcbiAgICAgICAgdmFsaWRUeXBlID0gdmFsaWRUeXBlLnRyaW0oKTtcbiAgICAgICAgaWYgKHZhbGlkVHlwZS5jaGFyQXQoMCkgPT09IFwiLlwiKSB7XG4gICAgICAgICAgICBpZiAoZmlsZS5uYW1lLnRvTG93ZXJDYXNlKCkuaW5kZXhPZih2YWxpZFR5cGUudG9Mb3dlckNhc2UoKSwgZmlsZS5uYW1lLmxlbmd0aCAtIHZhbGlkVHlwZS5sZW5ndGgpICE9PSAtMSkgcmV0dXJuIHRydWU7XG4gICAgICAgIH0gZWxzZSBpZiAoL1xcL1xcKiQvLnRlc3QodmFsaWRUeXBlKSkge1xuICAgICAgICAgICAgLy8gVGhpcyBpcyBzb21ldGhpbmcgbGlrZSBhIGltYWdlLyogbWltZSB0eXBlXG4gICAgICAgICAgICBpZiAoYmFzZU1pbWVUeXBlID09PSB2YWxpZFR5cGUucmVwbGFjZSgvXFwvLiokLywgXCJcIikpIHJldHVybiB0cnVlO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgaWYgKG1pbWVUeXBlID09PSB2YWxpZFR5cGUpIHJldHVybiB0cnVlO1xuICAgICAgICB9XG4gICAgfVxuICAgIHJldHVybiBmYWxzZTtcbn07XG4vLyBBdWdtZW50IGpRdWVyeVxuaWYgKHR5cGVvZiBqUXVlcnkgIT09IFwidW5kZWZpbmVkXCIgJiYgalF1ZXJ5ICE9PSBudWxsKSBqUXVlcnkuZm4uZHJvcHpvbmUgPSBmdW5jdGlvbihvcHRpb25zKSB7XG4gICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgICAgcmV0dXJuIG5ldyAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5KHRoaXMsIG9wdGlvbnMpO1xuICAgIH0pO1xufTtcbi8vIERyb3B6b25lIGZpbGUgc3RhdHVzIGNvZGVzXG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkFEREVEID0gXCJhZGRlZFwiO1xuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5RVUVVRUQgPSBcInF1ZXVlZFwiO1xuLy8gRm9yIGJhY2t3YXJkcyBjb21wYXRpYmlsaXR5LiBOb3csIGlmIGEgZmlsZSBpcyBhY2NlcHRlZCwgaXQncyBlaXRoZXIgcXVldWVkXG4vLyBvciB1cGxvYWRpbmcuXG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkFDQ0VQVEVEID0gJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5RVUVVRUQ7XG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LlVQTE9BRElORyA9IFwidXBsb2FkaW5nXCI7XG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LlBST0NFU1NJTkcgPSAkM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LlVQTE9BRElORzsgLy8gYWxpYXNcbiQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkuQ0FOQ0VMRUQgPSBcImNhbmNlbGVkXCI7XG4kM2VkMjY5ZjJmMGZiMjI0YiRleHBvcnQkMmUyYmNkODczOWFlMDM5LkVSUk9SID0gXCJlcnJvclwiO1xuJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOS5TVUNDRVNTID0gXCJzdWNjZXNzXCI7XG4vKlxuXG4gQnVnZml4IGZvciBpT1MgNiBhbmQgN1xuIFNvdXJjZTogaHR0cDovL3N0YWNrb3ZlcmZsb3cuY29tL3F1ZXN0aW9ucy8xMTkyOTA5OS9odG1sNS1jYW52YXMtZHJhd2ltYWdlLXJhdGlvLWJ1Zy1pb3NcbiBiYXNlZCBvbiB0aGUgd29yayBvZiBodHRwczovL2dpdGh1Yi5jb20vc3RvbWl0YS9pb3MtaW1hZ2VmaWxlLW1lZ2FwaXhlbFxuXG4gKi8gLy8gRGV0ZWN0aW5nIHZlcnRpY2FsIHNxdWFzaCBpbiBsb2FkZWQgaW1hZ2UuXG4vLyBGaXhlcyBhIGJ1ZyB3aGljaCBzcXVhc2ggaW1hZ2UgdmVydGljYWxseSB3aGlsZSBkcmF3aW5nIGludG8gY2FudmFzIGZvciBzb21lIGltYWdlcy5cbi8vIFRoaXMgaXMgYSBidWcgaW4gaU9TNiBkZXZpY2VzLiBUaGlzIGZ1bmN0aW9uIGZyb20gaHR0cHM6Ly9naXRodWIuY29tL3N0b21pdGEvaW9zLWltYWdlZmlsZS1tZWdhcGl4ZWxcbmxldCAkM2VkMjY5ZjJmMGZiMjI0YiR2YXIkZGV0ZWN0VmVydGljYWxTcXVhc2ggPSBmdW5jdGlvbihpbWcpIHtcbiAgICBsZXQgaXcgPSBpbWcubmF0dXJhbFdpZHRoO1xuICAgIGxldCBpaCA9IGltZy5uYXR1cmFsSGVpZ2h0O1xuICAgIGxldCBjYW52YXMgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiY2FudmFzXCIpO1xuICAgIGNhbnZhcy53aWR0aCA9IDE7XG4gICAgY2FudmFzLmhlaWdodCA9IGloO1xuICAgIGxldCBjdHggPSBjYW52YXMuZ2V0Q29udGV4dChcIjJkXCIpO1xuICAgIGN0eC5kcmF3SW1hZ2UoaW1nLCAwLCAwKTtcbiAgICBsZXQgeyBkYXRhOiBkYXRhICB9ID0gY3R4LmdldEltYWdlRGF0YSgxLCAwLCAxLCBpaCk7XG4gICAgLy8gc2VhcmNoIGltYWdlIGVkZ2UgcGl4ZWwgcG9zaXRpb24gaW4gY2FzZSBpdCBpcyBzcXVhc2hlZCB2ZXJ0aWNhbGx5LlxuICAgIGxldCBzeSA9IDA7XG4gICAgbGV0IGV5ID0gaWg7XG4gICAgbGV0IHB5ID0gaWg7XG4gICAgd2hpbGUocHkgPiBzeSl7XG4gICAgICAgIGxldCBhbHBoYSA9IGRhdGFbKHB5IC0gMSkgKiA0ICsgM107XG4gICAgICAgIGlmIChhbHBoYSA9PT0gMCkgZXkgPSBweTtcbiAgICAgICAgZWxzZSBzeSA9IHB5O1xuICAgICAgICBweSA9IGV5ICsgc3kgPj4gMTtcbiAgICB9XG4gICAgbGV0IHJhdGlvID0gcHkgLyBpaDtcbiAgICBpZiAocmF0aW8gPT09IDApIHJldHVybiAxO1xuICAgIGVsc2UgcmV0dXJuIHJhdGlvO1xufTtcbi8vIEEgcmVwbGFjZW1lbnQgZm9yIGNvbnRleHQuZHJhd0ltYWdlXG4vLyAoYXJncyBhcmUgZm9yIHNvdXJjZSBhbmQgZGVzdGluYXRpb24pLlxudmFyICQzZWQyNjlmMmYwZmIyMjRiJHZhciRkcmF3SW1hZ2VJT1NGaXggPSBmdW5jdGlvbihjdHgsIGltZywgc3gsIHN5LCBzdywgc2gsIGR4LCBkeSwgZHcsIGRoKSB7XG4gICAgbGV0IHZlcnRTcXVhc2hSYXRpbyA9ICQzZWQyNjlmMmYwZmIyMjRiJHZhciRkZXRlY3RWZXJ0aWNhbFNxdWFzaChpbWcpO1xuICAgIHJldHVybiBjdHguZHJhd0ltYWdlKGltZywgc3gsIHN5LCBzdywgc2gsIGR4LCBkeSwgZHcsIGRoIC8gdmVydFNxdWFzaFJhdGlvKTtcbn07XG4vLyBCYXNlZCBvbiBNaW5pZnlKcGVnXG4vLyBTb3VyY2U6IGh0dHA6Ly93d3cucGVycnkuY3ovZmlsZXMvRXhpZlJlc3RvcmVyLmpzXG4vLyBodHRwOi8vZWxpY29uLmJsb2c1Ny5mYzIuY29tL2Jsb2ctZW50cnktMjA2Lmh0bWxcbmNsYXNzICQzZWQyNjlmMmYwZmIyMjRiJHZhciRFeGlmUmVzdG9yZSB7XG4gICAgc3RhdGljIGluaXRDbGFzcygpIHtcbiAgICAgICAgdGhpcy5LRVlfU1RSID0gXCJBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWmFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6MDEyMzQ1Njc4OSsvPVwiO1xuICAgIH1cbiAgICBzdGF0aWMgZW5jb2RlNjQoaW5wdXQpIHtcbiAgICAgICAgbGV0IG91dHB1dCA9IFwiXCI7XG4gICAgICAgIGxldCBjaHIxID0gdW5kZWZpbmVkO1xuICAgICAgICBsZXQgY2hyMiA9IHVuZGVmaW5lZDtcbiAgICAgICAgbGV0IGNocjMgPSBcIlwiO1xuICAgICAgICBsZXQgZW5jMSA9IHVuZGVmaW5lZDtcbiAgICAgICAgbGV0IGVuYzIgPSB1bmRlZmluZWQ7XG4gICAgICAgIGxldCBlbmMzID0gdW5kZWZpbmVkO1xuICAgICAgICBsZXQgZW5jNCA9IFwiXCI7XG4gICAgICAgIGxldCBpID0gMDtcbiAgICAgICAgd2hpbGUodHJ1ZSl7XG4gICAgICAgICAgICBjaHIxID0gaW5wdXRbaSsrXTtcbiAgICAgICAgICAgIGNocjIgPSBpbnB1dFtpKytdO1xuICAgICAgICAgICAgY2hyMyA9IGlucHV0W2krK107XG4gICAgICAgICAgICBlbmMxID0gY2hyMSA+PiAyO1xuICAgICAgICAgICAgZW5jMiA9IChjaHIxICYgMykgPDwgNCB8IGNocjIgPj4gNDtcbiAgICAgICAgICAgIGVuYzMgPSAoY2hyMiAmIDE1KSA8PCAyIHwgY2hyMyA+PiA2O1xuICAgICAgICAgICAgZW5jNCA9IGNocjMgJiA2MztcbiAgICAgICAgICAgIGlmIChpc05hTihjaHIyKSkgZW5jMyA9IGVuYzQgPSA2NDtcbiAgICAgICAgICAgIGVsc2UgaWYgKGlzTmFOKGNocjMpKSBlbmM0ID0gNjQ7XG4gICAgICAgICAgICBvdXRwdXQgPSBvdXRwdXQgKyB0aGlzLktFWV9TVFIuY2hhckF0KGVuYzEpICsgdGhpcy5LRVlfU1RSLmNoYXJBdChlbmMyKSArIHRoaXMuS0VZX1NUUi5jaGFyQXQoZW5jMykgKyB0aGlzLktFWV9TVFIuY2hhckF0KGVuYzQpO1xuICAgICAgICAgICAgY2hyMSA9IGNocjIgPSBjaHIzID0gXCJcIjtcbiAgICAgICAgICAgIGVuYzEgPSBlbmMyID0gZW5jMyA9IGVuYzQgPSBcIlwiO1xuICAgICAgICAgICAgaWYgKCEoaSA8IGlucHV0Lmxlbmd0aCkpIGJyZWFrO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBvdXRwdXQ7XG4gICAgfVxuICAgIHN0YXRpYyByZXN0b3JlKG9yaWdGaWxlQmFzZTY0LCByZXNpemVkRmlsZUJhc2U2NCkge1xuICAgICAgICBpZiAoIW9yaWdGaWxlQmFzZTY0Lm1hdGNoKFwiZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCxcIikpIHJldHVybiByZXNpemVkRmlsZUJhc2U2NDtcbiAgICAgICAgbGV0IHJhd0ltYWdlID0gdGhpcy5kZWNvZGU2NChvcmlnRmlsZUJhc2U2NC5yZXBsYWNlKFwiZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCxcIiwgXCJcIikpO1xuICAgICAgICBsZXQgc2VnbWVudHMgPSB0aGlzLnNsaWNlMlNlZ21lbnRzKHJhd0ltYWdlKTtcbiAgICAgICAgbGV0IGltYWdlID0gdGhpcy5leGlmTWFuaXB1bGF0aW9uKHJlc2l6ZWRGaWxlQmFzZTY0LCBzZWdtZW50cyk7XG4gICAgICAgIHJldHVybiBgZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCwke3RoaXMuZW5jb2RlNjQoaW1hZ2UpfWA7XG4gICAgfVxuICAgIHN0YXRpYyBleGlmTWFuaXB1bGF0aW9uKHJlc2l6ZWRGaWxlQmFzZTY0LCBzZWdtZW50cykge1xuICAgICAgICBsZXQgZXhpZkFycmF5ID0gdGhpcy5nZXRFeGlmQXJyYXkoc2VnbWVudHMpO1xuICAgICAgICBsZXQgbmV3SW1hZ2VBcnJheSA9IHRoaXMuaW5zZXJ0RXhpZihyZXNpemVkRmlsZUJhc2U2NCwgZXhpZkFycmF5KTtcbiAgICAgICAgbGV0IGFCdWZmZXIgPSBuZXcgVWludDhBcnJheShuZXdJbWFnZUFycmF5KTtcbiAgICAgICAgcmV0dXJuIGFCdWZmZXI7XG4gICAgfVxuICAgIHN0YXRpYyBnZXRFeGlmQXJyYXkoc2VnbWVudHMpIHtcbiAgICAgICAgbGV0IHNlZyA9IHVuZGVmaW5lZDtcbiAgICAgICAgbGV0IHggPSAwO1xuICAgICAgICB3aGlsZSh4IDwgc2VnbWVudHMubGVuZ3RoKXtcbiAgICAgICAgICAgIHNlZyA9IHNlZ21lbnRzW3hdO1xuICAgICAgICAgICAgaWYgKHNlZ1swXSA9PT0gMjU1ICYgc2VnWzFdID09PSAyMjUpIHJldHVybiBzZWc7XG4gICAgICAgICAgICB4Kys7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIFtdO1xuICAgIH1cbiAgICBzdGF0aWMgaW5zZXJ0RXhpZihyZXNpemVkRmlsZUJhc2U2NCwgZXhpZkFycmF5KSB7XG4gICAgICAgIGxldCBpbWFnZURhdGEgPSByZXNpemVkRmlsZUJhc2U2NC5yZXBsYWNlKFwiZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCxcIiwgXCJcIik7XG4gICAgICAgIGxldCBidWYgPSB0aGlzLmRlY29kZTY0KGltYWdlRGF0YSk7XG4gICAgICAgIGxldCBzZXBhcmF0ZVBvaW50ID0gYnVmLmluZGV4T2YoMjU1LCAzKTtcbiAgICAgICAgbGV0IG1hZSA9IGJ1Zi5zbGljZSgwLCBzZXBhcmF0ZVBvaW50KTtcbiAgICAgICAgbGV0IGF0byA9IGJ1Zi5zbGljZShzZXBhcmF0ZVBvaW50KTtcbiAgICAgICAgbGV0IGFycmF5ID0gbWFlO1xuICAgICAgICBhcnJheSA9IGFycmF5LmNvbmNhdChleGlmQXJyYXkpO1xuICAgICAgICBhcnJheSA9IGFycmF5LmNvbmNhdChhdG8pO1xuICAgICAgICByZXR1cm4gYXJyYXk7XG4gICAgfVxuICAgIHN0YXRpYyBzbGljZTJTZWdtZW50cyhyYXdJbWFnZUFycmF5KSB7XG4gICAgICAgIGxldCBoZWFkID0gMDtcbiAgICAgICAgbGV0IHNlZ21lbnRzID0gW107XG4gICAgICAgIHdoaWxlKHRydWUpe1xuICAgICAgICAgICAgdmFyIGxlbmd0aDtcbiAgICAgICAgICAgIGlmIChyYXdJbWFnZUFycmF5W2hlYWRdID09PSAyNTUgJiByYXdJbWFnZUFycmF5W2hlYWQgKyAxXSA9PT0gMjE4KSBicmVhaztcbiAgICAgICAgICAgIGlmIChyYXdJbWFnZUFycmF5W2hlYWRdID09PSAyNTUgJiByYXdJbWFnZUFycmF5W2hlYWQgKyAxXSA9PT0gMjE2KSBoZWFkICs9IDI7XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBsZW5ndGggPSByYXdJbWFnZUFycmF5W2hlYWQgKyAyXSAqIDI1NiArIHJhd0ltYWdlQXJyYXlbaGVhZCArIDNdO1xuICAgICAgICAgICAgICAgIGxldCBlbmRQb2ludCA9IGhlYWQgKyBsZW5ndGggKyAyO1xuICAgICAgICAgICAgICAgIGxldCBzZWcgPSByYXdJbWFnZUFycmF5LnNsaWNlKGhlYWQsIGVuZFBvaW50KTtcbiAgICAgICAgICAgICAgICBzZWdtZW50cy5wdXNoKHNlZyk7XG4gICAgICAgICAgICAgICAgaGVhZCA9IGVuZFBvaW50O1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgaWYgKGhlYWQgPiByYXdJbWFnZUFycmF5Lmxlbmd0aCkgYnJlYWs7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHNlZ21lbnRzO1xuICAgIH1cbiAgICBzdGF0aWMgZGVjb2RlNjQoaW5wdXQpIHtcbiAgICAgICAgbGV0IG91dHB1dCA9IFwiXCI7XG4gICAgICAgIGxldCBjaHIxID0gdW5kZWZpbmVkO1xuICAgICAgICBsZXQgY2hyMiA9IHVuZGVmaW5lZDtcbiAgICAgICAgbGV0IGNocjMgPSBcIlwiO1xuICAgICAgICBsZXQgZW5jMSA9IHVuZGVmaW5lZDtcbiAgICAgICAgbGV0IGVuYzIgPSB1bmRlZmluZWQ7XG4gICAgICAgIGxldCBlbmMzID0gdW5kZWZpbmVkO1xuICAgICAgICBsZXQgZW5jNCA9IFwiXCI7XG4gICAgICAgIGxldCBpID0gMDtcbiAgICAgICAgbGV0IGJ1ZiA9IFtdO1xuICAgICAgICAvLyByZW1vdmUgYWxsIGNoYXJhY3RlcnMgdGhhdCBhcmUgbm90IEEtWiwgYS16LCAwLTksICssIC8sIG9yID1cbiAgICAgICAgbGV0IGJhc2U2NHRlc3QgPSAvW15BLVphLXowLTlcXCtcXC9cXD1dL2c7XG4gICAgICAgIGlmIChiYXNlNjR0ZXN0LmV4ZWMoaW5wdXQpKSBjb25zb2xlLndhcm4oXCJUaGVyZSB3ZXJlIGludmFsaWQgYmFzZTY0IGNoYXJhY3RlcnMgaW4gdGhlIGlucHV0IHRleHQuXFxuVmFsaWQgYmFzZTY0IGNoYXJhY3RlcnMgYXJlIEEtWiwgYS16LCAwLTksICcrJywgJy8nLGFuZCAnPSdcXG5FeHBlY3QgZXJyb3JzIGluIGRlY29kaW5nLlwiKTtcbiAgICAgICAgaW5wdXQgPSBpbnB1dC5yZXBsYWNlKC9bXkEtWmEtejAtOVxcK1xcL1xcPV0vZywgXCJcIik7XG4gICAgICAgIHdoaWxlKHRydWUpe1xuICAgICAgICAgICAgZW5jMSA9IHRoaXMuS0VZX1NUUi5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtcbiAgICAgICAgICAgIGVuYzIgPSB0aGlzLktFWV9TVFIuaW5kZXhPZihpbnB1dC5jaGFyQXQoaSsrKSk7XG4gICAgICAgICAgICBlbmMzID0gdGhpcy5LRVlfU1RSLmluZGV4T2YoaW5wdXQuY2hhckF0KGkrKykpO1xuICAgICAgICAgICAgZW5jNCA9IHRoaXMuS0VZX1NUUi5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtcbiAgICAgICAgICAgIGNocjEgPSBlbmMxIDw8IDIgfCBlbmMyID4+IDQ7XG4gICAgICAgICAgICBjaHIyID0gKGVuYzIgJiAxNSkgPDwgNCB8IGVuYzMgPj4gMjtcbiAgICAgICAgICAgIGNocjMgPSAoZW5jMyAmIDMpIDw8IDYgfCBlbmM0O1xuICAgICAgICAgICAgYnVmLnB1c2goY2hyMSk7XG4gICAgICAgICAgICBpZiAoZW5jMyAhPT0gNjQpIGJ1Zi5wdXNoKGNocjIpO1xuICAgICAgICAgICAgaWYgKGVuYzQgIT09IDY0KSBidWYucHVzaChjaHIzKTtcbiAgICAgICAgICAgIGNocjEgPSBjaHIyID0gY2hyMyA9IFwiXCI7XG4gICAgICAgICAgICBlbmMxID0gZW5jMiA9IGVuYzMgPSBlbmM0ID0gXCJcIjtcbiAgICAgICAgICAgIGlmICghKGkgPCBpbnB1dC5sZW5ndGgpKSBicmVhaztcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gYnVmO1xuICAgIH1cbn1cbiQzZWQyNjlmMmYwZmIyMjRiJHZhciRFeGlmUmVzdG9yZS5pbml0Q2xhc3MoKTtcbi8qXG4gKiBjb250ZW50bG9hZGVkLmpzXG4gKlxuICogQXV0aG9yOiBEaWVnbyBQZXJpbmkgKGRpZWdvLnBlcmluaSBhdCBnbWFpbC5jb20pXG4gKiBTdW1tYXJ5OiBjcm9zcy1icm93c2VyIHdyYXBwZXIgZm9yIERPTUNvbnRlbnRMb2FkZWRcbiAqIFVwZGF0ZWQ6IDIwMTAxMDIwXG4gKiBMaWNlbnNlOiBNSVRcbiAqIFZlcnNpb246IDEuMlxuICpcbiAqIFVSTDpcbiAqIGh0dHA6Ly9qYXZhc2NyaXB0Lm53Ym94LmNvbS9Db250ZW50TG9hZGVkL1xuICogaHR0cDovL2phdmFzY3JpcHQubndib3guY29tL0NvbnRlbnRMb2FkZWQvTUlULUxJQ0VOU0VcbiAqLyAvLyBAd2luIHdpbmRvdyByZWZlcmVuY2Vcbi8vIEBmbiBmdW5jdGlvbiByZWZlcmVuY2VcbmxldCAkM2VkMjY5ZjJmMGZiMjI0YiR2YXIkY29udGVudExvYWRlZCA9IGZ1bmN0aW9uKHdpbiwgZm4pIHtcbiAgICBsZXQgZG9uZSA9IGZhbHNlO1xuICAgIGxldCB0b3AgPSB0cnVlO1xuICAgIGxldCBkb2MgPSB3aW4uZG9jdW1lbnQ7XG4gICAgbGV0IHJvb3QgPSBkb2MuZG9jdW1lbnRFbGVtZW50O1xuICAgIGxldCBhZGQgPSBkb2MuYWRkRXZlbnRMaXN0ZW5lciA/IFwiYWRkRXZlbnRMaXN0ZW5lclwiIDogXCJhdHRhY2hFdmVudFwiO1xuICAgIGxldCByZW0gPSBkb2MuYWRkRXZlbnRMaXN0ZW5lciA/IFwicmVtb3ZlRXZlbnRMaXN0ZW5lclwiIDogXCJkZXRhY2hFdmVudFwiO1xuICAgIGxldCBwcmUgPSBkb2MuYWRkRXZlbnRMaXN0ZW5lciA/IFwiXCIgOiBcIm9uXCI7XG4gICAgdmFyIGluaXQgPSBmdW5jdGlvbihlKSB7XG4gICAgICAgIGlmIChlLnR5cGUgPT09IFwicmVhZHlzdGF0ZWNoYW5nZVwiICYmIGRvYy5yZWFkeVN0YXRlICE9PSBcImNvbXBsZXRlXCIpIHJldHVybjtcbiAgICAgICAgKGUudHlwZSA9PT0gXCJsb2FkXCIgPyB3aW4gOiBkb2MpW3JlbV0ocHJlICsgZS50eXBlLCBpbml0LCBmYWxzZSk7XG4gICAgICAgIGlmICghZG9uZSAmJiAoZG9uZSA9IHRydWUpKSByZXR1cm4gZm4uY2FsbCh3aW4sIGUudHlwZSB8fCBlKTtcbiAgICB9O1xuICAgIHZhciBwb2xsID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIHRyeSB7XG4gICAgICAgICAgICByb290LmRvU2Nyb2xsKFwibGVmdFwiKTtcbiAgICAgICAgfSBjYXRjaCAoZSkge1xuICAgICAgICAgICAgc2V0VGltZW91dChwb2xsLCA1MCk7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIGluaXQoXCJwb2xsXCIpO1xuICAgIH07XG4gICAgaWYgKGRvYy5yZWFkeVN0YXRlICE9PSBcImNvbXBsZXRlXCIpIHtcbiAgICAgICAgaWYgKGRvYy5jcmVhdGVFdmVudE9iamVjdCAmJiByb290LmRvU2Nyb2xsKSB7XG4gICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICAgIHRvcCA9ICF3aW4uZnJhbWVFbGVtZW50O1xuICAgICAgICAgICAgfSBjYXRjaCAoZXJyb3IpIHtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGlmICh0b3ApIHBvbGwoKTtcbiAgICAgICAgfVxuICAgICAgICBkb2NbYWRkXShwcmUgKyBcIkRPTUNvbnRlbnRMb2FkZWRcIiwgaW5pdCwgZmFsc2UpO1xuICAgICAgICBkb2NbYWRkXShwcmUgKyBcInJlYWR5c3RhdGVjaGFuZ2VcIiwgaW5pdCwgZmFsc2UpO1xuICAgICAgICByZXR1cm4gd2luW2FkZF0ocHJlICsgXCJsb2FkXCIsIGluaXQsIGZhbHNlKTtcbiAgICB9XG59O1xuZnVuY3Rpb24gJDNlZDI2OWYyZjBmYjIyNGIkdmFyJF9fZ3VhcmRfXyh2YWx1ZSwgdHJhbnNmb3JtKSB7XG4gICAgcmV0dXJuIHR5cGVvZiB2YWx1ZSAhPT0gXCJ1bmRlZmluZWRcIiAmJiB2YWx1ZSAhPT0gbnVsbCA/IHRyYW5zZm9ybSh2YWx1ZSkgOiB1bmRlZmluZWQ7XG59XG5mdW5jdGlvbiAkM2VkMjY5ZjJmMGZiMjI0YiR2YXIkX19ndWFyZE1ldGhvZF9fKG9iaiwgbWV0aG9kTmFtZSwgdHJhbnNmb3JtKSB7XG4gICAgaWYgKHR5cGVvZiBvYmogIT09IFwidW5kZWZpbmVkXCIgJiYgb2JqICE9PSBudWxsICYmIHR5cGVvZiBvYmpbbWV0aG9kTmFtZV0gPT09IFwiZnVuY3Rpb25cIikgcmV0dXJuIHRyYW5zZm9ybShvYmosIG1ldGhvZE5hbWUpO1xuICAgIGVsc2UgcmV0dXJuIHVuZGVmaW5lZDtcbn1cblxuXG5leHBvcnQgeyQzZWQyNjlmMmYwZmIyMjRiJGV4cG9ydCQyZTJiY2Q4NzM5YWUwMzkgYXMgZGVmYXVsdCwgJDNlZDI2OWYyZjBmYjIyNGIkZXhwb3J0JDJlMmJjZDg3MzlhZTAzOSBhcyBEcm9wem9uZX07XG4vLyMgc291cmNlTWFwcGluZ1VSTD1kcm9wem9uZS5tanMubWFwXG4iLCIvLyBUaGUgbW9kdWxlIGNhY2hlXG52YXIgX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fID0ge307XG5cbi8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG5mdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuXHR2YXIgY2FjaGVkTW9kdWxlID0gX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fW21vZHVsZUlkXTtcblx0aWYgKGNhY2hlZE1vZHVsZSAhPT0gdW5kZWZpbmVkKSB7XG5cdFx0cmV0dXJuIGNhY2hlZE1vZHVsZS5leHBvcnRzO1xuXHR9XG5cdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG5cdHZhciBtb2R1bGUgPSBfX3dlYnBhY2tfbW9kdWxlX2NhY2hlX19bbW9kdWxlSWRdID0ge1xuXHRcdC8vIG5vIG1vZHVsZS5pZCBuZWVkZWRcblx0XHQvLyBubyBtb2R1bGUubG9hZGVkIG5lZWRlZFxuXHRcdGV4cG9ydHM6IHt9XG5cdH07XG5cblx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG5cdF9fd2VicGFja19tb2R1bGVzX19bbW9kdWxlSWRdKG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG5cdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG5cdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbn1cblxuIiwiLy8gZGVmaW5lIGdldHRlciBmdW5jdGlvbnMgZm9yIGhhcm1vbnkgZXhwb3J0c1xuX193ZWJwYWNrX3JlcXVpcmVfXy5kID0gKGV4cG9ydHMsIGRlZmluaXRpb24pID0+IHtcblx0Zm9yKHZhciBrZXkgaW4gZGVmaW5pdGlvbikge1xuXHRcdGlmKF9fd2VicGFja19yZXF1aXJlX18ubyhkZWZpbml0aW9uLCBrZXkpICYmICFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywga2V5KSkge1xuXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIGtleSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGRlZmluaXRpb25ba2V5XSB9KTtcblx0XHR9XG5cdH1cbn07IiwiX193ZWJwYWNrX3JlcXVpcmVfXy5vID0gKG9iaiwgcHJvcCkgPT4gKE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmosIHByb3ApKSIsIi8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbl9fd2VicGFja19yZXF1aXJlX18uciA9IChleHBvcnRzKSA9PiB7XG5cdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuXHR9XG5cdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG59OyIsImNvbnN0IHsgRHJvcHpvbmUgfSA9IHJlcXVpcmUoXCJkcm9wem9uZVwiKTtcclxuY29uc3QgVFlQRV9DSEVDS0lOID0gMVxyXG5jb25zdCBUWVBFX0lOU1RBTExfQVBQID0gMlxyXG5jb25zdCBUWVBFX1ZJREVPX1dBVENIID0gM1xyXG5jb25zdCBUWVBFX1NPQ0lBTCA9IDRcclxuRHJvcHpvbmUuYXV0b0Rpc2NvdmVyID0gZmFsc2U7XHJcbnJlcXVpcmUoJ2pxdWVyeS1yZXBlYXRlci1mb3JtJyk7XHJcblxyXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XHJcbiAgY29uc3QgdGFza0NvbnRyb2xsZXIgPSBuZXcgVGFza0NvbnRyb2xzKCk7XHJcbn0pO1xyXG5cclxuY2xhc3MgVGFza0NvbnRyb2xzIHtcclxuICBjb25zdHJ1Y3RvcigpIHtcclxuICAgIHRoaXMuX2luaXRTaW5nbGVJbWFnZVVwbG9hZCgpO1xyXG4gICAgdGhpcy5faW5pdEdhbGxlcmllcygpO1xyXG4gICAgdGhpcy5faW5pdExvY2F0aW9uKCk7XHJcbiAgICB0aGlzLl9jaGFuZ2VUYXNrVHlwZSgpO1xyXG4gIH1cclxuICAvKipcclxuICAgKlxyXG4gICAqIEBwcml2YXRlXHJcbiAgICovXHJcbiAgX2NoYW5nZVRhc2tUeXBlKCkge1xyXG4gICAgY29uc3QgX3RoaXMgPSB0aGlzO1xyXG4gICAgJCgnLnRhc2stdHlwZSBzZWxlY3QnKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKGV2ZW50KSB7XHJcbiAgICAgIGNvbnN0IHR5cGUgPSAkKGV2ZW50LmN1cnJlbnRUYXJnZXQpLnZhbCgpXHJcbiAgICAgIF90aGlzLl9hY3Rpb25UYXNrVHlwZSh0eXBlKVxyXG4gICAgfSk7XHJcblxyXG4gICAgLy8gR2V0IGZpcnN0IGluaXRhbCB0YXNrIHR5cGVcclxuICAgIGNvbnN0IHR5cGVJbml0ID0gJCgnLnRhc2stdHlwZSBzZWxlY3Qgb3B0aW9uOnNlbGVjdGVkJykudmFsKCk7XHJcbiAgICBpZih0eXBlSW5pdCkge1xyXG4gICAgICBfdGhpcy5fYWN0aW9uVGFza1R5cGUodHlwZUluaXQpXHJcbiAgICB9XHJcbiAgfVxyXG4gIC8qKlxyXG4gICAqXHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICAgX2FjdGlvblRhc2tUeXBlKHR5cGUpIHtcclxuICAgICAgc3dpdGNoKHBhcnNlSW50KHR5cGUpKSB7XHJcbiAgICAgICAgY2FzZSBUWVBFX0NIRUNLSU46XHJcbiAgICAgICAgICAkKCcud3JhcC10eXBlLWNoZWNraW4nKS5zaG93KCk7XHJcbiAgICAgICAgICAkKCcud3JhcC10eXBlLXNvY2lhbCcpLmhpZGUoKTtcclxuICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgIGNhc2UgVFlQRV9JTlNUQUxMX0FQUDpcclxuICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgIGNhc2UgVFlQRV9WSURFT19XQVRDSDpcclxuICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgIGNhc2UgVFlQRV9TT0NJQUw6XHJcbiAgICAgICAgICAkKCcud3JhcC10eXBlLXNvY2lhbCcpLnNob3coKTtcclxuICAgICAgICAgICQoJy53cmFwLXR5cGUtY2hlY2tpbicpLmhpZGUoKTtcclxuICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgIGRlZmF1bHQ6XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyh0eXBlLCBUWVBFX0NIRUNLSU4sIHR5cGUgPT0gVFlQRV9DSEVDS0lOKVxyXG4gICAgICB9XHJcbiAgfVxyXG4gIC8qKlxyXG4gICAqXHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICBfaW5pdExvY2F0aW9uKCkge1xyXG4gICAgbGV0IF90aGlzID0gdGhpcztcclxuICAgICQoJy5qcy1yZXBlYXRlcicpLnJlcGVhdGVyKHtcclxuICAgICAgaXNGaXJzdEl0ZW1VbmRlbGV0YWJsZTogdHJ1ZSxcclxuICAgICAgc2hvdzogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGxldCAkdGhpcyA9ICQodGhpcyk7XHJcbiAgICAgICAgJHRoaXMuYXR0cignZGF0YS1yZXBlYXRlci1pdGVtJywgJycpO1xyXG4gICAgICAgIGxldCBidG5EZWxldGUgPSAkdGhpcy5maW5kKCcuanMtZGVsZXRlJyk7XHJcbiAgICAgICAgaWYgKGJ0bkRlbGV0ZS5sZW5ndGgpIHtcclxuICAgICAgICAgIGJ0bkRlbGV0ZS5hdHRyKCdkYXRhLXJlcGVhdGVyLWRlbGV0ZScsICcnKTtcclxuICAgICAgICAgIGJ0bkRlbGV0ZS5hdHRyKCdkYXRhLWlkJywgJycpO1xyXG4gICAgICAgICAgYnRuRGVsZXRlLnJlbW92ZUNsYXNzKCdqcy1kZWxldGUnKTsvL2Fsd2F5cyBzZXQgYm90dG9tIG9mIGNvbmRpdGlvblxyXG4gICAgICAgIH1cclxuICAgICAgICAkdGhpcy5zbGlkZURvd24oKTtcclxuICAgICAgfSxcclxuICAgIH0pO1xyXG5cclxuICAgICQoJy5qcy1kZWxldGUnKS5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgIF90aGlzLl9kZWxldGVMb2NhdGlvbkl0ZW0oJCh0aGlzKSk7XHJcbiAgICB9KTtcclxuICB9XHJcblxyXG4gIC8qKlxyXG4gICAqXHJcbiAgICogQHBhcmFtICR0aGlzIGpxdWVyeSBlbGVtZW50XHJcbiAgICogQHByaXZhdGVcclxuICAgKi9cclxuICBfZGVsZXRlTG9jYXRpb25JdGVtKCR0aGlzKSB7XHJcbiAgICBsZXQgbG9jYXRpb25JZCA9ICR0aGlzLmF0dHIoJ2RhdGEtaWQnKTtcclxuICAgIGxldCBlRnJvbSA9ICR0aGlzLmNsb3Nlc3QoJ2Zvcm0nKTtcclxuXHJcbiAgICAkdGhpcy5jbG9zZXN0KCdkaXZbZGF0YS1yZXBlYXRlci1pdGVtPScgKyBsb2NhdGlvbklkICsgJ10nKS5yZW1vdmUoKTtcclxuICAgIGVGcm9tLmFwcGVuZCgnPGlucHV0IHR5cGU9XCJoaWRkZW5cIiBuYW1lPVwibGlzdF9kZWxldGVbXVwiIHZhbHVlPVwiJysgbG9jYXRpb25JZCArJ1wiPicpO1xyXG4gICAgcmV0dXJuIHRydWU7XHJcbiAgfVxyXG5cclxuICAvLyBTaW5nbGUgSW1hZ2UgVXBsb2FkIGluaXRpYWxpemF0aW9uXHJcbiAgX2luaXRTaW5nbGVJbWFnZVVwbG9hZCgpIHtcclxuICAgIHRoaXMuX3NpbmdsZUltYWdlVXBsb2FkRXhhbXBsZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0YXNrSW1nQ292ZXInKTtcclxuICAgIGNvbnN0IHNpbmdsZUltYWdlVXBsb2FkID0gbmV3IFNpbmdsZUltYWdlVXBsb2FkKHRoaXMuX3NpbmdsZUltYWdlVXBsb2FkRXhhbXBsZSk7XHJcbiAgfVxyXG5cclxuICBfaW5pdEdhbGxlcmllcygpIHtcclxuICAgIG5ldyBEcm9wem9uZSgnI3Rhc2tHYWxsZXJ5Jywge1xyXG4gICAgICBhdXRvUHJvY2Vzc1F1ZXVlOiBmYWxzZSxcclxuICAgICAgbWF4RmlsZXNpemU6IDIsXHJcbiAgICAgIGRpY3REZWZhdWx0TWVzc2FnZTogXCJEcm9wIHlvdXIgZmlsZXMgaGVyZSFcIixcclxuICAgICAgdXJsOiAnaHR0cHM6Ly9odHRwYmluLm9yZy9wb3MnLCAvLyBhcGkgcG9zdCBmaWxlXHJcbiAgICAgIHJlbW92ZWRmaWxlOiBmdW5jdGlvbihmaWxlKSB7XHJcbiAgICAgICAgY29uc29sZS5sb2coZmlsZS51cGxvYWQuZmlsZW5hbWUpO1xyXG4gICAgICB9LFxyXG4gICAgICBpbml0OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgbGV0IG15RHJvcHpvbmUgPSB0aGlzO1xyXG4gICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCJidXR0b25bdHlwZT1zdWJtaXRdXCIpLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICAvLyBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAvLyBlLnN0b3BQcm9wYWdhdGlvbigpO1xyXG5cclxuICAgICAgICAgIC8vIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2lucHV0W25hbWU9XCJnYWxsZXJ5XCJdJykuZmlsZXMgPSBteURyb3B6b25lLmdldFF1ZXVlZEZpbGVzKCk7XHJcbiAgICAgICAgICAvLyBsZXQgcGVuZGluZ0ZpbGVzID0gbXlEcm9wem9uZS5nZXRRdWV1ZWRGaWxlcygpO1xyXG4gICAgICAgICAgLy8gcGVuZGluZ0ZpbGVzLmZvckVhY2goJGZpbGUgPT4ge1xyXG4gICAgICAgICAgLy8gICBjb25zdCByZWFkZXIgID0gbmV3IEZpbGVSZWFkZXIoKTtcclxuICAgICAgICAgIC8vICAgcmVhZGVyLnJlYWRBc0RhdGFVUkwoJCgnaW5wdXRbbmFtZT1cImdhbGxlcnlcIl0nKS5wcm9wKFwiZmlsZXNcIikpO1xyXG4gICAgICAgICAgLy8gICBjb25zb2xlLmxvZygkZmlsZSk7XHJcbiAgICAgICAgICAvLyB9KTtcclxuXHJcbiAgICAgICAgICAvL2NvbnNvbGUubG9nKG15RHJvcHpvbmUuZ2V0UXVldWVkRmlsZXMoKSk7XHJcbiAgICAgICAgICAvL2NvbnNvbGUubG9nKG15RHJvcHpvbmUuZ2V0QWNjZXB0ZWRGaWxlcygpKTtcclxuICAgICAgICAgIC8vbXlEcm9wem9uZS5wcm9jZXNzUXVldWUoKTtcclxuICAgICAgICB9KTtcclxuICAgICAgICB0aGlzLm9uKCdzdWNjZXNzJywgZnVuY3Rpb24gKGZpbGUsIHJlc3BvbnNlVGV4dCkge1xyXG4gICAgICAgICAgY29uc29sZS5sb2cocmVzcG9uc2VUZXh0KTtcclxuICAgICAgICB9KTtcclxuICAgICAgfSxcclxuICAgICAgYWNjZXB0ZWRGaWxlczogJ2ltYWdlLyonLFxyXG4gICAgICB0aHVtYm5haWxXaWR0aDogMTYwLFxyXG4gICAgICBwcmV2aWV3VGVtcGxhdGU6IERyb3B6b25lVGVtcGxhdGVzLnByZXZpZXdUZW1wbGF0ZSxcclxuICAgIH0pO1xyXG4gIH1cclxufVxyXG4iXSwibmFtZXMiOlsicmVxdWlyZSIsIkRyb3B6b25lIiwiVFlQRV9DSEVDS0lOIiwiVFlQRV9JTlNUQUxMX0FQUCIsIlRZUEVfVklERU9fV0FUQ0giLCJUWVBFX1NPQ0lBTCIsImF1dG9EaXNjb3ZlciIsIiQiLCJkb2N1bWVudCIsInJlYWR5IiwidGFza0NvbnRyb2xsZXIiLCJUYXNrQ29udHJvbHMiLCJfaW5pdFNpbmdsZUltYWdlVXBsb2FkIiwiX2luaXRHYWxsZXJpZXMiLCJfaW5pdExvY2F0aW9uIiwiX2NoYW5nZVRhc2tUeXBlIiwiX3RoaXMiLCJvbiIsImV2ZW50IiwidHlwZSIsImN1cnJlbnRUYXJnZXQiLCJ2YWwiLCJfYWN0aW9uVGFza1R5cGUiLCJ0eXBlSW5pdCIsInBhcnNlSW50Iiwic2hvdyIsImhpZGUiLCJjb25zb2xlIiwibG9nIiwicmVwZWF0ZXIiLCJpc0ZpcnN0SXRlbVVuZGVsZXRhYmxlIiwiJHRoaXMiLCJhdHRyIiwiYnRuRGVsZXRlIiwiZmluZCIsImxlbmd0aCIsInJlbW92ZUNsYXNzIiwic2xpZGVEb3duIiwiX2RlbGV0ZUxvY2F0aW9uSXRlbSIsImxvY2F0aW9uSWQiLCJlRnJvbSIsImNsb3Nlc3QiLCJyZW1vdmUiLCJhcHBlbmQiLCJfc2luZ2xlSW1hZ2VVcGxvYWRFeGFtcGxlIiwiZ2V0RWxlbWVudEJ5SWQiLCJzaW5nbGVJbWFnZVVwbG9hZCIsIlNpbmdsZUltYWdlVXBsb2FkIiwiYXV0b1Byb2Nlc3NRdWV1ZSIsIm1heEZpbGVzaXplIiwiZGljdERlZmF1bHRNZXNzYWdlIiwidXJsIiwicmVtb3ZlZGZpbGUiLCJmaWxlIiwidXBsb2FkIiwiZmlsZW5hbWUiLCJpbml0IiwibXlEcm9wem9uZSIsInF1ZXJ5U2VsZWN0b3IiLCJhZGRFdmVudExpc3RlbmVyIiwiZSIsInJlc3BvbnNlVGV4dCIsImFjY2VwdGVkRmlsZXMiLCJ0aHVtYm5haWxXaWR0aCIsInByZXZpZXdUZW1wbGF0ZSIsIkRyb3B6b25lVGVtcGxhdGVzIl0sInNvdXJjZVJvb3QiOiIifQ==
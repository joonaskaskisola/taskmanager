import React from 'react';
import { render } from 'react-dom';

import { useRouterHistory } from 'react-router';
import { createHashHistory } from 'history';

const appHistory = useRouterHistory(createHashHistory)({
    queryKey: false
});

export default class Row extends React.Component {
    render() {
        let fields = [], self = this;

        this.props.fields.forEach(function(field) {
            /**
             * To future me: I'm sorry.
             *
             * Checks if field name has dot, and if so, goes through the row object one by one (splitted by dot)
             * until string is found
             */
            if (field.indexOf('.') !== -1) {
                let key = self.props.row, splitted = field.split('.').forEach(function(split) {
                    key = key[split];

                    if (typeof key === "string") {
                        fields.push(<div key={"field-" + field + self.props.row.id} className="column">{ key }</div>);
                    }
                });
            } else {
                fields.push(<div key={"field-" + field + self.props.row.id} className="column">{ self.props.row[field] }</div>);
            }
        });

        return <div className="row" onClick={ () => {appHistory.push('/' + this.props.app + "/" + this.props.row.id); this.props.viewRow(this.props.row.id) }}>
            {fields}
        </div>
    }
}

import {
  AddItemAction,
  RemoveItemAction,
  HighlightItemAction,
} from '../actions/products';
import { Item } from '../interfaces/item';
import { State } from '../interfaces/state';

export const defaultState = [];

type ActionTypes =
  | AddItemAction
  | RemoveItemAction
  | HighlightItemAction
  | Record<string, never>;

export default function products(
  state: Item[] = defaultState,
  action: ActionTypes = {},
): State['products'] {
  switch (action.type) {
    case 'ADD_ITEM': {
      const addItemAction = action as AddItemAction;
      // Add object to products array
      const newState = [
        ...state,
        {
          id: addItemAction.id,
          choiceId: addItemAction.choiceId,
          groupId: addItemAction.groupId,
          value: addItemAction.value,
          label: addItemAction.label,
          active: true,
          highlighted: false,
          customProperties: addItemAction.customProperties,
          placeholder: addItemAction.placeholder || false,
          keyCode: null,
        },
      ];

      return newState.map((obj: Item) => {
        const item = obj;
        item.highlighted = false;

        return item;
      });
    }

    case 'REMOVE_ITEM': {
      // Set item to inactive
      return state.map((obj) => {
        const item = obj;
        if (item.id === action.id) {
          item.active = false;
        }

        return item;
      });
    }

    case 'HIGHLIGHT_ITEM': {
      const highlightItemAction = action as HighlightItemAction;

      return state.map((obj) => {
        const item = obj;
        if (item.id === highlightItemAction.id) {
          item.highlighted = highlightItemAction.highlighted;
        }

        return item;
      });
    }

    default: {
      return state;
    }
  }
}
